<?php

namespace ctl;

use core\Dispatcher;
use core\JsonRequest;
use core\JsonResponse;
use model\User;
use model\UserRates;
use model\Rates;

/**
 * Class Main
 * @package ctl
 */
class Main {

    /**
     * @var JsonRequest
     */
    public $request;

    /**
     * @var JsonResponse
     */
    public $response;

    /**
     * @param JsonRequest $request
     */
    function __construct(JsonRequest $request) {

        $this->request = $request;
        $this->response = $this->request->response();
    }

    /**
     * @return JsonResponse
     */
    public function getUserRate() {

        $this->response = $this->request->response();

        $uid = $this->request->data['user'];

        if ( $uid ) {

            $usr = new User();
            $usrIds = $usr->getUser($uid);

            $this->response->data['user'] = $usrIds->toArray();

            $urate = new UserRates();
            $userRatesIds = $urate->getRatesIdsByUserId( $usrIds[0]->id );

            $ratesIds = array();
            foreach( $userRatesIds as $userRate){
                $ratesIds[] = $userRate->rid;
            }

            $rate = new Rates();
            $rates = $rate->getRates( $ratesIds );

            $this->response->data['rates'] = $rates->toArray();
        }
        return $this->response;
    }

    public function importData(){

        $this->response = $this->request->response();

        $usr = new User();
        $usrData = $usr->getAll();

        $rates = new Rates();
        $ratesData = $rates->getAll();

        $urates = new UserRates();
        $uratesData = $urates->getAll();

        $config = Dispatcher::obj()->getConfig();
        $mdb = $config->mongo->_client;

        $db = $mdb->selectDB($config::DBNAME);

        //Create user collection//
        $db->dropCollection('user');
        $ucol = $db->createCollection('user');

        $uarr = $usrData->toArray();
        foreach( $uarr as $userItem ){
            $userItem['rates'] = array();
            $ucol->insert( $userItem );
        }
        //Create rate collection//
        $db->dropCollection('rate');
        $rcol = $db->createCollection('rate');

        $rarr = $ratesData->toArray();
        foreach( $rarr as $rateItem ){
            $rcol->insert( $rateItem );
        }
        //Fill user collection with rates data//
        foreach( $uratesData as $urItem ){
            $u = $ucol->findOne(array('id'=>$urItem->uid));
            $r = $rcol->findOne(array('id'=>$urItem->rid));
            array_push( $u['rates'], array('id'=>$r['_id'],'rname'=>$r['rname']) );
            $ucol->findAndModify(array('id'=>$urItem->uid),array('name'=>$u['name'], 'id'=>$urItem->uid, 'rates'=>$u['rates']));
        }
        //Cleanup user collection//
        foreach( $usrData as $uItem ){
            $u = $ucol->findOne(array('id'=>$uItem->id));
            $ucol->findAndModify(array('id'=>$uItem->id),array('name'=>$u['name'], 'rates'=>$u['rates']));
        }
        //Cleanup rates collection//
        foreach( $ratesData as $rItem ){
            $r = $rcol->findOne(array('id'=>$rItem->id));
            $rcol->findAndModify(array('id'=>$rItem->id),array('rname'=>$r['rname']));
        }

        //$error = $db->lastError();
        $this->response->data = array('import'=>'successfully done');

        return $this->response;
    }

    public function getMongoUserRate(){
        $this->response = $this->request->response();

        $uname = $this->request->data['user'];

        if ( $uname ) {

            $config = Dispatcher::obj()->getConfig();
            $mdb = $config->mongo->_client;

            $db = $mdb->selectDB($config::DBNAME);
            $ucol = $db->selectCollection('user');

            $user = $ucol->findOne(array('name'=>$uname));
            $user['user'] = array(array('name'=>$uname));

            $this->response->data = $user;
        }
        return $this->response;
    }
}

