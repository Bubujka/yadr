<?php

def_accessor('yadr\production', false);
def_accessor('yadr\login', null);
def_accessor('yadr\token', null);
def_accessor('yadr\app_id', null);

def('yadr\utf8', function($struct){
  foreach ($struct as $key => $value) {
      if (is_array($value)) {
          $struct[$key] = yadr\utf8($value);
      }
      elseif (is_string($value)) {
          $struct[$key] = utf8_encode($value);
      }
  }
  return $struct;
});

def('yadr\method', function($method, $params = array()){
  $login = yadr\login();
  $token = yadr\token();
  $app_id = yadr\app_id();
  if(yadr\production())
    $backend = 'https://api.direct.yandex.ru/json-api/v4/';
  else
    $backend = 'https://api-sandbox.direct.yandex.ru/json-api/v4/';
   
  $request = array(
      'token'=> $token, 
      'application_id'=> $app_id,
      'login'=> $login,
      'method'=> $method,
      'param'=> yadr\utf8($params),
      'locale'=> 'ru',
  );
   
  $request = json_encode($request);
   
  $opts = array(
      'http'=>array(
          'method'=>"POST",
          'content'=>$request,
      )
  ); 

  $context = stream_context_create($opts); 
   
  $result = @file_get_contents($backend, 0, $context);
  return json_decode($result, true);
});

def('yadr\create_wrappers', function($in_global_namespace = false){
  $methods = array(
    'AdImageAssociation', 'AdImage', 'ArchiveBanners', 'ArchiveCampaign', 'CreateInvoice', 'CreateNewForecast',
    'CreateNewReport', 'CreateNewSubclient', 'CreateNewWordstatReport', 'CreateOrUpdateBanners',
    'CreateOrUpdateCampaign', 'DeleteBanners', 'DeleteCampaign', 'DeleteForecastReport', 'DeleteReport',
    'DeleteWordstatReport', 'GetAvailableVersions', 'GetBalance', 'GetBannerPhrases', 'GetBannerPhrasesFilter',
    'GetBanners', 'GetBannersTags', 'GetCampaignsList', 'GetCampaignsListFilter', 'GetCampaignsParams',
    'GetCampaignsTags', 'GetChanges', 'GetClientInfo', 'GetClientsList', 'GetClientsUnits',
    'GetCreditLimits', 'GetEventsLog', 'GetForecast', 'GetForecastList', 'GetKeywordsSuggestion', 'GetRegions',
    'GetReportList', 'GetRetargetingGoals', 'GetRubrics', 'GetStatGoals', 'GetSubClients', 'GetSummaryStat',
    'GetTimeZones', 'GetVersion', 'GetWordstatReport', 'GetWordstatReportList', 'ModerateBanners', 'PayCampaigns',
    'PingAPI', 'ResumeBanners', 'ResumeCampaign', 'RetargetingCondition', 'Retargeting', 'SetAutoPrice',
    'StopBanners', 'StopCampaign', 'TransferMoney', 'UnArchiveBanners', 'UnArchiveCampaign',
    'UpdateBannersTags', 'UpdateCampaignsTags', 'UpdateClientInfo', 'UpdatePrices');


  foreach($methods as $m)
    def(($in_global_namespace ? '' : "yadr\\").$m, function($params = array()) use($m){
      return yadr\method($m, $params);
    });
  
});


yadr\create_wrappers();
