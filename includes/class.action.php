<?php


namespace beds_booking;


class Action_beds_booking

{

    /**
     * Get refresh token string
     * @return mixed
     */

    private function getRefToken()

    {

        return BEDS_REF_TOKEN;

    }


    /**
     * Refresh the Token and set it to db
     * call - $act->refreshToken();
     *
     * @return bool|int|\mysqli_result|resource|null
     */

    public function refreshToken()

    {

        if ($this->canCall()):

            $ch = curl_init();


            curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/authentication/token');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();

            $headers[] = 'Accept: application/json';

            $headers[] = 'Refreshtoken: ' . $this->getRefToken() . '';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


            $result = curl_exec($ch);

            if (curl_errno($ch)) {

                return 'Error:' . curl_error($ch);

            }

            curl_close($ch);

            $result = json_decode($result, true);

            $token = $result['token'];

            $exp = $result['expiresIn'];

            global $wpdb;

            $table = $wpdb->get_results("SELECT * FROM `beds_tokens`");

            if (empty($table)) {

                $res = $wpdb->query("insert into `beds_tokens` (`token`, `expin`) values ('$token',$exp)");

            } else {

                $res = $wpdb->query("update `beds_tokens` set token='$token', expin='$exp' where id=1");

            }

            $this->APIIterator();

            return $res;

        else:

            return false;

        endif;

    }


    /**
     * Create table with token
     * call - $act->createTokenTable();
     */

    public function createTokenTable()

    {

        global $wpdb;


        $sql = "CREATE TABLE IF NOT EXISTS `beds_tokens` (

  `id` int(11) NOT NULL auto_increment,   

  `token`  varchar(250) NOT NULL,

  `expin` int(7) NOT NULL,

  `date_token` timestamp NOT NULL,

   PRIMARY KEY  (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql);

    }


    public function createCalendarTable()

    {

        global $wpdb;

        $sql = "CREATE TABLE IF NOT EXISTS `beds_calendar` (

    `id` int(11) NOT NULL auto_increment,   

    `roomId`  int(10) NOT NULL,

    `propertyId` int(10) NOT NULL,

    `date` date NOT NULL,

    `avaliable` int(1) NOT NULL,

    `isBooked` int(1) NOT NULL,

    `minStay` int(3) NOT NULL,

    `maxStay` int(3) NOT NULL,

    `price1` float NOT NULL,

    `price2` float  NOT NULL,

    

   PRIMARY KEY  (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql);

    }


    /**
     * Create main table with data from each property and rooms
     * call - $act->createMainTable();
     */

    public function createMainTable()

    {

        global $wpdb;

        $sql = "CREATE TABLE IF NOT EXISTS `beds_properties` (

    `id` int(11) NOT NULL auto_increment,   

    `roomId`  int(10),

    `propertyId` int(10) NOT NULL,

    `nameProp` varchar(250),

    `nameRoom` varchar(250),

    `tel` varchar(15),

    `mail` varchar(60),

    `currency` varchar(10),

    `bookType` varchar(15),

    `site` varchar(250),

    `contactPersName` varchar(50),

    `contactPersLName` varchar(50),

    `fax` varchar(15),

    `address`varchar(150),

    `city` varchar(25),

    `state` varchar(50),

    `country` varchar(10),

    `postcode` varchar(10),

    `latitude` varchar(15),

    `longitude` varchar(15),

    `checkInStart` varchar(15),

    `checkInEnd` varchar(15),

    `checkOutEnd` varchar(15),

    `propertyDescription1en` varchar(250),

    `propertyDescription2en` varchar(250),

    `propertyDescriptionBookingPage1en` text,

    `propertyDescriptionBookingPage2en` text,

    `propertyDescription1sv` varchar(250),

    `propertyDescription2sv` varchar(250),

    `propertyDescriptionBookingPage1sv` text,

    `propertyDescriptionBookingPage2sv` text,

    `minPrice` int(10),

    `maxPeople` int(5),

    `maxAdult` int(5),

    `maxChildren` int(5),

    `images` text,

    `propKey` varchar(250),



   PRIMARY KEY  (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql);

    }


    /**
     * Method get`s the Token and refresh it if it`s need
     * return token string or verb Error
     * @return string|null
     */

    public function getToken()

    {

        global $wpdb;


        $isToken = $wpdb->get_results("SELECT * FROM `beds_tokens`");

        if (empty($isToken)) {

            if ($this->refreshToken()) {

                return $wpdb->get_var('select `token` from `beds_tokens` where id=1');

            } else {

                return 'error';

            }

        } else {

            $dateNow = date('Y-m-d H:i:s');

            $tokenDate = $wpdb->get_var("select `date_token` from `beds_tokens` where id=1");

            $dateEndToken = date('Y-m-d H:i:s', strtotime("+ 22 hours", strtotime($tokenDate)));

            if ((strtotime($dateEndToken) - strtotime($dateNow)) < 0) {

                if ($this->refreshToken()) {

                    return $wpdb->get_var('select `token` from `beds_tokens` where id=1');

                } else {

                    return 'error';

                }

            } else {

                return $wpdb->get_var('select `token` from `beds_tokens` where id=1');

            }

        }

    }


    /**
     * Method get`s all main property from start date to end
     * using API V2, url: https://beds24.com/api/v2/inventory/rooms/calendar
     * return associative array
     * @return mixed
     */

    public function getAllProp($startDate, $endDate)

    {

        if ($this->canCall()):

            $ch = curl_init();

            if (($timestamp = strtotime($startDate)) === false) {

                return 'error';

            } else {

                $startDate = date('Y-m-d', $timestamp);

            }

            if (($timestamp = strtotime($endDate)) === false) {

                return 'error';

            } else {

                $endDate = date('Y-m-d', $timestamp);

            }

            curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/inventory/rooms/calendar?startDate=' . $startDate . '&endDate=' . $endDate . '&includeNumAvail=true&includeMinStay=true&includeMaxStay=true&includeMultiplier=true&includeOverride=true&includePrices=true&includeLinkedPrices=true');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();

            $headers[] = 'Accept: application/json';

            $headers[] = 'Token: ' . $this->getToken() . '';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


            $result = curl_exec($ch);

            if (curl_errno($ch)) {

                return 'Error:' . curl_error($ch);

            }

            curl_close($ch);

            $this->APIIterator();

            return json_decode($result, true);

        else:

            return false;

        endif;

    }


    /**
     * Method get`s all data for property by IDs
     * @param $propIDs array it`s array with values only like array($val1,$val2...)
     * @return mixed
     */

    public function getPropDataByID($propIDs)

    {

        if ($this->canCall()):

            $ch = curl_init();


            $propURL = '';

            foreach ($propIDs as $propID) {

                $propURL .= 'id=' . $propID . '&';

            }

            curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/properties?' . $propURL . 'includeLanguages=all&includeTexts=all&includePictures=true&includeOffers=true&includePriceRules=true&includeAllRooms=true&includeUnitDetails=true');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();

            $headers[] = 'Accept: application/json';

            $headers[] = 'Token: ' . $this->getToken() . '';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


            $result = curl_exec($ch);

            if (curl_errno($ch)) {

                return 'Error:' . curl_error($ch);

            }

            curl_close($ch);

            $this->APIIterator();

            return json_decode($result, true);

        else:

            return false;

        endif;

    }


    public function getNumAdult($roomID)

    {

        global $wpdb;


        $res = $wpdb->get_var('select maxPeople from `beds_properties` where roomId=' . $roomID);

        if ($res) {

            return $res;

        } else {

            return false;

        }

    }


    public function getIsAvailable($roomID, $start, $end)
    {

        if ($this->canCall()) {

            $ch = curl_init();


            curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/inventory/rooms/availability?roomId=' . $roomID . '&startDate=' . $start . '&endDate=' . $end);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();

            $headers[] = 'Accept: application/json';

            $headers[] = 'Token: ' . $this->getToken() . '';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


            $result = curl_exec($ch);

            if (curl_errno($ch)) {

                echo 'Error:' . curl_error($ch);

            }

            curl_close($ch);

            $this->APIIterator();

            return json_decode($result, true);

        } else {

            return false;

        }


    }


    public function updateCalendar($startDate = null, $endDate = null)

    {

        if (is_null($startDate) and is_null($endDate) or (empty($startDate) or empty($endDate))) {

            $startDate = date('Y-m-d');

            $endDate = date('Y-m-d', strtotime('+ 1 year'));

        }


        global $wpdb;

        $data = $this->getAllProp($startDate, $endDate);

        if ($data['success']) {

            $propCount = $data['count'];

            $success = 0;

            foreach ($data['data'] as $datum) {

                $roomID = $datum['roomId'];

                $propertyID = $datum['propertyId'];

                $arrDates = array();

                $res = $wpdb->get_var("select `id` from `beds_properties` where propertyId='$propertyID'");

                if (empty($res)) {

                    $wpdb->query("INSERT INTO `beds_properties` (propertyId) value ('$propertyID')");

                }

                foreach ($datum['calendar'] as $calendar) {

                    if ($calendar['from'] == $calendar['to']) {

                        $date = $calendar['from'];

                        $aval = $calendar['numAvail'];

                        $minStay = $calendar['minStay'];

                        $maxStay = $calendar['maxStay'];

                        $price1 = $calendar['price1'];

                        $price2 = $calendar['price2'];

                        $isBook = $calendar['override'];

                        if ($isBook == 'none') {

                            $isBook = 1;

                        } else {

                            $isBook = 0;

                        }


                        $sql = "update `beds_calendar` set avaliable='$aval',isBooked='$isBook',price2='$price2', price1='$price1' where `date`='$date' and `roomId`='$roomID'";

                        if ($wpdb->query($sql)) {

                            $success++;

                        }

                    } else {

                        $origin = date_create($calendar['from']);

                        $target = date_create($calendar['to']);

                        $interval = date_diff($origin, $target);

                        $dateCount = $interval->format('%a');

                        $dates = array($calendar['from']);

                        $aval = $calendar['numAvail'];

                        $minStay = $calendar['minStay'];

                        $maxStay = $calendar['maxStay'];

                        $price1 = $calendar['price1'];

                        $price2 = $calendar['price2'];

                        $isBook = $calendar['override'];

                        if ($isBook == 'none') {

                            $isBook = 1;

                        } else {

                            $isBook = 0;

                        }

                        $sql = "update `beds_calendar` set avaliable='$aval',isBooked='$isBook',price2='$price2', price1='$price1' where date='$dates[0]' and roomId='$roomID'";


                        if ($wpdb->query($sql)) {

                            $success++;

                        }

                        for ($i = 0; $i < (int)$dateCount; $i++) {

                            $date = date('Y-m-d', strtotime('+ 1 day', strtotime($dates[$i])));

                            array_push($dates, $date);

                            $sql = "update `beds_calendar` set avaliable='$aval',isBooked='$isBook',price2='$price2', price1='$price1' where date='$date' and roomId='$roomID'";

                            if ($wpdb->query($sql)) {

                                $success++;

                            }

                        }

                    }

                }

            }

            return $success;

        } else {

            return false;

        }

    }


    /**
     * Method set data to tables, if date not set - start\end date will be from $now to 1 year ahead
     * @param null $startDate
     * @param null $endDate
     * @return bool
     */

    public function setDataInCalendar($startDate = null, $endDate = null)

    {

        if (is_null($startDate) and is_null($endDate)) {

            $startDate = date('Y-m-d');

            $endDate = date('Y-m-d', strtotime('+ 1 year'));

            $def = true;

        }

        global $wpdb;

        $firstData = $this->getAllProp($startDate, $endDate);


        if ($firstData['success']) {

            $propCount = $firstData['count'];

            $successIter = 0;

            foreach ($firstData['data'] as $datum) {

                $roomID = $datum['roomId'];

                $propertyID = $datum['propertyId'];

                $arrDates = array();

                $res = $wpdb->get_var("select `id` from `beds_properties` where propertyId='$propertyID'");

                if (empty($res)) {

                    $wpdb->query("INSERT INTO `beds_properties` (propertyId) value ('$propertyID')");

                }

                foreach ($datum['calendar'] as $calendar) {

                    if ($calendar['from'] == $calendar['to']) {

                        $date = $calendar['from'];

                        $aval = $calendar['numAvail'];

                        $bookable = $calendar['override'];

                        if ($bookable == 'none') {

                            $bookable = 1; // can book

                        } else {

                            $bookable = 0; //not book in this date

                        }

                        $minStay = $calendar['minStay'];

                        $maxStay = $calendar['maxStay'];

                        $price1 = $calendar['price1'];

                        $price2 = $calendar['price2'];

                        array_push($arrDates, "('" . $roomID . "','" . $propertyID . "','" . $date . "','" . $aval . "','" . $bookable . "','" . $minStay . "','" . $maxStay . "','" . $price1 . "','" . $price2 . "')");

                    } else {

                        $origin = date_create($calendar['from']);

                        $target = date_create($calendar['to']);

                        $interval = date_diff($origin, $target);

                        $dateCount = $interval->format('%a');

                        $dates = array($calendar['from']);

                        $aval = $calendar['numAvail'];

                        $bookable = $calendar['override'];

                        if ($bookable == 'none') {

                            $bookable = 1; // can book

                        } else {

                            $bookable = 0; //not book in this date

                        }

                        $minStay = $calendar['minStay'];

                        $maxStay = $calendar['maxStay'];

                        $price1 = $calendar['price1'];

                        $price2 = $calendar['price2'];

                        array_push($arrDates, "('" . $roomID . "','" . $propertyID . "','" . $dates[0] . "','" . $aval . "','" . $bookable . "','" . $minStay . "','" . $maxStay . "','" . $price1 . "','" . $price2 . "')");

                        for ($i = 0; $i < (int)$dateCount; $i++) {

                            $date = date('Y-m-d', strtotime('+ 1 day', strtotime($dates[$i])));

                            array_push($dates, $date);

                            array_push($arrDates, "('" . $roomID . "','" . $propertyID . "','" . $date . "','" . $aval . "','" . $bookable . "','" . $minStay . "','" . $maxStay . "','" . $price1 . "','" . $price2 . "')");

                        }

                    }

                }

                $res = $wpdb->get_var('SELECT count(`id`) from `beds_calendar`');


                if ((int)$res == 0) {

                    $sql = 'insert into `beds_calendar` (roomId,propertyId,date,avaliable,isBooked,minStay,maxStay,price1,price2) values ' . implode(',', $arrDates);

                    if ($wpdb->query($sql)) {

                        $successIter++;

                    }

                } else {


                    $data = explode(',', $arrDates[0]);


                    $newRes = $wpdb->get_var('SELECT id from `beds_calendar` where `date`="' . str_replace(array('\'', '('), '', $data[2]) . '" and roomId="' . str_replace(array('\'', '('), '', $data[0]) . '" ');

                    if (!$newRes) {

                        $sql = 'insert into `beds_calendar` (roomId,propertyId,date,avaliable,isBooked,minStay,maxStay,price1,price2) values ' . implode(',', $arrDates);

                        $wpdb->query($sql);

                    }


                }

            }

            if ($successIter == 0) {

                return false;

            } else {

                // log to file iter

                return true;

            }

        } else {

            return false;

        }

    }


    public function createPriceRulesTable()

    {

        global $wpdb;


        $sql = "CREATE TABLE IF NOT EXISTS `beds_prices_rules` (

    `id` int(11) NOT NULL auto_increment,   

    `roomId`  int(10) NOT NULL,

    `idPrice` int(5) NOT NULL,

    `minimumStay` int(3) NOT NULL,

    `maximumStay` int(5) NOT NULL,

    `pricePercent` float NOT NULL,

    

   PRIMARY KEY  (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql);

    }


    public function setDataInPropTable()

    {

        global $wpdb;

        $props = $wpdb->get_results("select propertyId from `beds_properties`");

        $propArr = array();

        foreach ($props as $prop) {

            array_push($propArr, $prop->propertyId);

        }

        $res = $this->getPropDataByID($propArr);


        if ($res['success']) {

            foreach ($res['data'] as $datum) {

                $propID = $datum['id'];

                $name = $datum['name'];

                $currency = $datum['currency'];

                $address = $datum['address'];

                $city = $datum['city'];

                $state = $datum['state'];

                $country = $datum['country'];

                $postcode = $datum['postcode'];

                $latitude = $datum['latitude'];

                $longitude = $datum['longitude'];

                $checkInStart = $datum['checkInStart'];

                $checkInEnd = $datum['checkInEnd'];

                $checkOutEnd = $datum['checkOutEnd'];

                foreach ($datum['texts'] as $text) {

                    if ($text['language'] == 'en') {

                        $propertyDescription1en = $text['propertyDescription1'];

                        $propertyDescription2en = $text['propertyDescription2'];

                        $propertyDescriptionBookingPage1en = $text['propertyDescriptionBookingPage1'];

                        $propertyDescriptionBookingPage2en = $text['propertyDescriptionBookingPage2'];

                    }

                    if ($text['language'] == 'sv') {

                        $propertyDescription1sv = $text['propertyDescription1'];

                        $propertyDescription2sv = $text['propertyDescription2'];

                        $propertyDescriptionBookingPage1sv = $text['propertyDescriptionBookingPage1'];

                        $propertyDescriptionBookingPage2sv = $text['propertyDescriptionBookingPage2'];

                    }

                }

                foreach ($datum['roomTypes'] as $roomType) {

                    $roomID = $roomType['id'];

                    $nameRoom = $roomType['name'];

                    $minPrice = $roomType['minPrice'];

                    $maxPeople = $roomType['maxPeople'];

                    $maxAdult = $roomType['maxAdult'];

                    $maxChildren = $roomType['maxChildren'];

                    $countPrices = count($roomType['priceRules']);

                    foreach ($roomType['priceRules'] as $priceRule) {

                        $idPrice = $priceRule['id'];

                        $minimumStay = $priceRule['minimumStay'];

                        $maximumStay = $priceRule['maximumStay'];

                        $pricePercent = $priceRule['priceLinking']['offsetMultiplier'];


//                        $sql = "update `beds_prices_rules` set idPrice='$idPrice', minimumStay='$minimumStay',

//                        maximumStay='$maximumStay', pricePercent='$pricePercent' where roomId='$roomID'";

                        $sql = "insert into `beds_prices_rules` (idPrice,minimumStay,maximumStay,pricePercent,roomId) values 

                            ('$idPrice','$minimumStay','$maximumStay','$pricePercent','$roomID')";

                        $wpdb->query($sql);

                    }

                }


                $sql = "insert into `beds_properties` (roomId,nameProp,nameRoom,currency,address,city,state,country,postcode,

latitude,longitude,checkInStart,checkInEnd,checkOutEnd,propertyDescription1en,propertyDescription1sv,

propertyDescription2en,propertyDescription2sv,propertyDescriptionBookingPage1en,propertyDescriptionBookingPage1sv,

propertyDescriptionBookingPage2en,propertyDescriptionBookingPage2sv,minPrice,maxPeople,maxAdult,maxChildren,propertyId) values 

('$roomID','$name','$nameRoom','$currency','$address','$city','$state','$country','$postcode','$latitude','$longitude','$checkInStart',

'$checkInEnd','$checkOutEnd','$propertyDescription1en','$propertyDescription1sv','$propertyDescription2en','$propertyDescription2sv',

'$propertyDescriptionBookingPage1en','$propertyDescriptionBookingPage1sv','$propertyDescriptionBookingPage2en',

'$propertyDescriptionBookingPage2sv','$minPrice','$maxPeople','$maxAdult','$maxChildren','$propID')";

                if ($wpdb->query($sql)) {

                    $photos = array();

                    $photo = $this->getPhotos($propID);

                    if (!empty($photo)) {

                        foreach ($photo->hosted as $item) {

                            if (!empty($item->url)) {

                                array_push($photos, $item->url);

                            }

                        }

                        foreach ($photo->external as $item) {

                            if (!empty($item->url)) {

                                array_push($photos, $item->url);

                            }

                        }

                    }

                    if (!empty($photos)) {

                        $img = implode(',', $photos);

                        $wpdb->query("update `beds_properties` set images='$img' where propertyId='$propID'");

                    }

                }

            }

        } else {

            return false;

        }


    }


    public function updatePropTable()

    {

        global $wpdb;

        $props = $wpdb->get_results("select propertyId from `beds_properties`");

        $propArr = array();

        foreach ($props as $prop) {

            array_push($propArr, $prop->propertyId);

        }

        $res = $this->getPropDataByID($propArr);

        if ($res['success']) {

            $success = 0;

            foreach ($res['data'] as $datum) {

                $propID = $datum['id'];

                $name = $datum['name'];

                $currency = $datum['currency'];

                $address = $datum['address'];

                $city = $datum['city'];

                $state = $datum['state'];

                $country = $datum['country'];

                $postcode = $datum['postcode'];

                $latitude = $datum['latitude'];

                $longitude = $datum['longitude'];

                $checkInStart = $datum['checkInStart'];

                $checkInEnd = $datum['checkInEnd'];

                $checkOutEnd = $datum['checkOutEnd'];

                foreach ($datum['texts'] as $text) {

                    if ($text['language'] == 'en') {

                        $propertyDescription1en = $text['propertyDescription1'];

                        $propertyDescription2en = $text['propertyDescription2'];

                        $propertyDescriptionBookingPage1en = $text['propertyDescriptionBookingPage1'];

                        $propertyDescriptionBookingPage2en = $text['propertyDescriptionBookingPage2'];

                    }

                    if ($text['language'] == 'sv') {

                        $propertyDescription1sv = $text['propertyDescription1'];

                        $propertyDescription2sv = $text['propertyDescription2'];

                        $propertyDescriptionBookingPage1sv = $text['propertyDescriptionBookingPage1'];

                        $propertyDescriptionBookingPage2sv = $text['propertyDescriptionBookingPage2'];

                    }

                }

                foreach ($datum['roomTypes'] as $roomType) {

                    $roomID = $roomType['id'];

                    $nameRoom = $roomType['name'];

                    $minPrice = $roomType['minPrice'];

                    $maxPeople = $roomType['maxPeople'];

                    $maxAdult = $roomType['maxAdult'];

                    $maxChildren = $roomType['maxChildren'] ?? 0;

                    $countPrices = count($roomType['priceRules']);

                    foreach ($roomType['priceRules'] as $priceRule) {

                        $idPrice = $priceRule['id'];

                        $minimumStay = $priceRule['minimumStay'];

                        $maximumStay = $priceRule['maximumStay'];

                        $pricePercent = $priceRule['priceLinking']['offsetMultiplier'];


                        $sql = "update `beds_prices_rules` set idPrice='$idPrice', minimumStay='$minimumStay',

                        maximumStay='$maximumStay', pricePercent='$pricePercent' where roomId='$roomID'";


                        $wpdb->query($sql);

                    }

                }


                $sql = "update `beds_properties` set roomId='$roomID', nameProp='$name', nameRoom='$nameRoom',

                currency='$currency', address='$address', city='$city', state='$state', country='$country',

                postcode='$postcode',latitude='$latitude', longitude='$longitude', checkInStart='$checkInStart',

                checkInEnd='$checkInEnd',checkOutEnd='$checkOutEnd', propertyDescription1en='$propertyDescription1en',

                propertyDescription1sv='$propertyDescription1sv', propertyDescription2en='$propertyDescription2en',

                propertyDescription2sv='$propertyDescription2sv', propertyDescriptionBookingPage1en='$propertyDescriptionBookingPage1en',

                propertyDescriptionBookingPage1sv='$propertyDescriptionBookingPage1sv', propertyDescriptionBookingPage2en='$propertyDescriptionBookingPage2en',

                propertyDescriptionBookingPage2sv='$propertyDescriptionBookingPage2sv', minPrice='$minPrice',

                maxPeople='$maxPeople', maxAdult='$maxAdult', maxChildren='$maxChildren' where propertyId='$propID'";

                if ($wpdb->query($sql)) {

                    $success++;

                }

//                if (){

                $photos = array();

                $photo = $this->getPhotos($propID);

                if (!empty($photo)) {

                    foreach ($photo->hosted as $item) {

                        if (!empty($item->url)) {

                            array_push($photos, $item->url);

                        }

                    }

                    foreach ($photo->external as $item) {

                        if (!empty($item->url)) {

                            array_push($photos, $item->url);

                        }

                    }

                }

//                var_dump($photos); exit();


                if (!empty($photos)) {

                    $img = implode(',', $photos);

                    if ($wpdb->query("update `beds_properties` set images='$img' where propertyId='$propID'")) {

                        $success++;

                    }

                }

//                }

            }

            return $success;

        } else {

            return false;

        }

    }


    public function getRoomPriceByDays($days, $start, $end, $postID)

    {

        global $wpdb;


        $days = intval($days);


        $table = $wpdb->prefix . 'postmeta';

        $products = $wpdb->get_results("SELECT meta_value FROM $table WHERE post_id = '$postID' AND meta_key = '_product_beds_id'");

        $room_id = $products[0]->meta_value;


        if ($days < 7) {

            $sql = "select price1 from `beds_calendar` WHERE (date BETWEEN '$start' AND '$end') and roomId='$room_id'";

        } else {

            $sql = "select price2 from `beds_calendar` WHERE (date BETWEEN '$start' AND '$end') and roomId='$room_id'";

        }


        $prices = $wpdb->get_results($sql, ARRAY_N);

        $total = 0;

        for ($i = 0; $i < $days; $i++) {

            $total += $prices[$i]['0'];

        }


        return $total;

    }


    public function getPhotos($propID)

    {

        if ($this->canCall()):

            global $wpdb;

            $propKey = $wpdb->get_var("select propKey from `beds_properties` where propertyId=" . $propID);

            $post = [

                'authentication' => [

                    'apiKey' => BEDS_API_KEY,

                    'propKey' => $propKey,

                ],

                'images' => 'true',

            ];


            $ch = curl_init('https://api.beds24.com/json/getPropertyContent');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));


            $response = curl_exec($ch);

            curl_close($ch);

            $res = json_decode($response);

            $this->APIIterator();

            return $res->getPropertyContent[0]->images ?? 'error';

        else:

            return false;

        endif;


    }


    private function canCall()

    {

        if ($this->checkAPICalls() < 60) {

            return true;

        } else {

            return false;

        }

    }


    private function checkAPICalls()

    {

        $file = "apiiter.txt";

        $handle = file_get_contents($file);

        $data = explode("\n", $handle);

        return count($data);

    }

    private function APIIterator()

    {

        $file = "apiiter.txt";

        $handle = fopen($file, "a");

        $time = time();

        $str = "$time\n";

        fwrite($handle, $str);

        fclose($handle);

    }

    public function clearAPIIter()

    {

        $file = "apiiter.txt";

        $handle = file_get_contents($file);

        $data = explode("\n", $handle);

        $fiveMinBack = strtotime('-300 second', time());

        foreach ($data as $item => $val) {

            if ($val < $fiveMinBack) {

                unset($data[$item]);

            }

        }

        $data = implode("\n", $data);

        $handle = fopen($file, "w");

        $data = $data . "\n";

        fwrite($handle, $data);

        fclose($handle);

    }


    public function updateAvailByRoom($idRoom, $notAvailDateArr)

    {

        global $wpdb;

        foreach ($notAvailDateArr as $date) {

            $sql = "update `beds_calendar` set avaliable = 0 where `date`='$date' and roomId='$idRoom'";

            $wpdb->query($sql);

        }

    }


    public function setBookingOnAPI($orderID)

    {
        $re = '';

        $order = wc_get_order($orderID);

        foreach ($order->get_items() as $item_id => $item) {

            $from = $item->get_meta('booked_from');

            $to = $item->get_meta('booked_to');

            $persons = $item->get_meta('persons');

            $prodID = $item->get_data()['product_id'];

            $roomId = get_post_meta($prodID, '_product_beds_id', true);

            $data = $order->get_data();

            $billing_first_name = $data['billing']['first_name'];

            $billing_last_name = $data['billing']['last_name'];

            $billing_address_1 = $data['billing']['address_1'];

            $billing_city = $data['billing']['city'];

            $billing_state = $data['billing']['state'];

            $billing_postcode = $data['billing']['postcode'];

            $billing_country = $data['billing']['country'];

            $mail = $data['billing']['email'];

            $tel = $data['billing']['phone'];

            $total = $item->get_total();
            $quantity = $item->get_quantity();
            $invoiceArr = [
                [
                    "type" => "charge",
                    "qty"=>$quantity,
                    "amount"=>$total,
                    "lineTotal"=>$total,
                    "description"=>'[ROOMNAME1] [FIRSTNIGHT] - [LEAVINGDAY]'
                ]//,
//                [
//                    "type" => "payment",
//                    "qty"=>$quantity,
//                    "amount"=>$total,
//                    "lineTotal"=> '-'.$total,
//                    "description"=>'[ROOMNAME1] [FIRSTNIGHT] - [LEAVINGDAY]'
//                ]
            ];

            foreach( $order->get_items('fee') as $item_fee_id => $item_fee ){

                $fee_name = $item_fee->get_name();
                $fee_total = $item_fee->get_total();
                $fee_quantity = $item_fee->get_quantity();

                array_push($invoiceArr,  [
                    "type" => "charge",
                    "qty"=>$fee_quantity,
                    "amount"=>$fee_total,
                    "lineTotal"=>$fee_total,
                    "description"=>$fee_name
                ]);

//                array_push($invoiceArr, [
//                    "type" => "payment",
//                    "qty"=>$fee_quantity,
//                    "amount"=>$fee_total,
//                    "lineTotal"=> '-'.$fee_total,
//                    "description"=>$fee_name
//                ]);
            }


            $post = [

                [

                    'roomId' => $roomId,

                    "status" => "confirmed",

                    "arrival" => $from,

                    "departure" => $to,

                    "numAdult" => $persons,

                    "numChild" => 0,

                    "firstName" => $billing_first_name,

                    "lastName" => $billing_last_name,

                    "email" => $mail,

                    "mobile" => $tel,

                    "address" => $billing_address_1,

                    "city" => $billing_city,

                    "state" => $billing_state,

                    "postcode" => $billing_postcode,

                    "country" => $billing_country,

                    "invoiceItems" => $invoiceArr

                ]

            ];

//            var_dump($post);
//exit();


            $re = $this->sendBooking($post);

            $this->setBookingInDB($from, $to, $roomId);

        }

        return $re;

    }


    private function setBookingInDB($dateStart, $dateEnd, $roomID)

    {

        global $wpdb;


        $origin = date_create($dateStart);

        $target = date_create($dateEnd);

        $interval = date_diff($origin, $target);

        $dateCount = $interval->format('%a');

        $dates = array($dateStart);

        for ($i = 0; $i < (int)$dateCount; $i++) {

            $date = date('Y-m-d', strtotime('+ 1 day', strtotime($dates[$i])));

            array_push($dates, $date);

            $sql = "update `beds_calendar` set avaliable=0 where date='$date' and roomId='$roomID'";

            $res = $wpdb->query($sql);

        }

    }


    private function sendBooking($arr)

    {

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/api/v2/bookings');
//        curl_setopt($ch, CURLOPT_URL, 'https://beds24.com/'); for tests

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));


        $headers = array();

        $headers[] = 'Accept: application/json';

        $headers[] = 'Token: ' . $this->getToken() . '';

        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $result = curl_exec($ch);

        if (curl_errno($ch)) {

            echo 'Error:' . curl_error($ch);

        }

        curl_close($ch);


//        var_dump($result);
        return $result;
    }

    public function showModal($stat,$type)
    {
        if ($stat == 'Error'){

            if ($type == 'beds24Connect'){
                return $this->modal($stat, 'Not able to automatically process the booking. We will try to contact you soon');
            }

        }
    }

    public function modal($stat, $text)
    {
        ob_start();
        ?>
        <style>
            .beds-modal{
                color: black;
                background-color: white;
                width: 50%;
                height: auto;
                padding: 20px;
                border: 3px solid #e83939;
                border-radius: 20px;
                text-align: center;
                position: fixed;
                z-index: 999999;
                left: 25%;
                top: 40%;
            }
            .beds-modal-btn button{
                color: white;
                background: #e83939;
                font-size: 20px;
                font-weight: 500;
                border-radius: 20px;
            }
            .owf-modal{
                background: rgba(140, 140, 140, 0.59);
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                z-index: 99999;
                right: 0;
                /*display: none;*/
            }
        </style>
        <div class="owf-modal" id="beds24-modal">
            <div class="beds-modal">
                <div class="beds-modal-header">
                    <h3 id="modal-head-beds"><?= $stat; ?></h3>
                </div>
                <div class="beds-modal-body">
                    <p id="modal-text-beds"><?= $text; ?></p>
                </div>
                <div class="beds-modal-btn">
                    <button id="modal-ok-beds">OK</button>
                </div>
            </div>
        </div>

        <script>
            jQuery('#modal-ok-beds').on('click', function (){
                jQuery('#beds24-modal').css('display','none');
            })

        </script>
        <?php
        return ob_get_clean();
    }

}

