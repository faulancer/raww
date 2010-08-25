<?php



  defined('RAWW_REQ_BEGIN_TIME') or define('REQ_BEGIN_TIME',microtime(true));
  
  define('DS',                    DIRECTORY_SEPARATOR);
  define('RAWW_CORE',             realpath(dirname(__FILE__)).DS);
  define('RAWW_CORE_LIBS',        realpath(RAWW_CORE.'lib').DS);
  define('RAWW_ROOT',             realpath(dirname(RAWW_CORE)).DS);
  define('RAWW_APP',              realpath(dirname(RAWW_WEBROOT)).DS);
  define('RAWW_APP_MODULES',      realpath(RAWW_APP.'modules').DS);
  define('RAWW_APP_LOCALES',      realpath(RAWW_APP.'locale').DS);
  define('RAWW_APP_JS',           realpath(RAWW_WEBROOT.'js').DS);
  define('RAWW_APP_CSS',          realpath(RAWW_WEBROOT.'css').DS);
  define('RAWW_APP_CONFIG',       realpath(RAWW_APP.'config').DS);
  define('RAWW_APP_LIBS',         realpath(RAWW_APP.'lib').DS);
  define('RAWW_APP_VENDOR',       realpath(RAWW_APP.'vendor').DS);
  define('RAWW_APPS_VENDOR',      realpath(RAWW_ROOT.'vendor').DS);
  
  define('RAWW_APP_TMP',          realpath(RAWW_APP.'tmp').DS);
  define('RAWW_APP_CACHE',        realpath(RAWW_APP_TMP.'cache').DS);
  define('RAWW_APP_LOGS',         realpath(RAWW_APP_TMP.'logs').DS);
  define('RAWW_APP_TASKS',        realpath(RAWW_APP_TMP.'tasks').DS);

  require_once(RAWW_CORE.'app.php');

  Path::register('core', RAWW_CORE);
  Path::register('lib', RAWW_CORE_LIBS);
  Path::register('lib', RAWW_APP_LIBS);
  Path::register('vendor', RAWW_APPS_VENDOR);
  Path::register('vendor', RAWW_APP_VENDOR);
  Path::register('modules', RAWW_APP_MODULES);
  Path::register('files', RAWW_WEBROOT.'files');

  //Load settings
  Config::load('settings');
  Config::load('connections');
  Config::load('routes');
  
  error_reporting(((Config::read('App.Debug.level')== 0) ? 0 : E_ALL));
  
  //Session autostart?
  if(Config::read('App.Session.autostart')){
    Session::init();
  }
  
  //Load app bootstrap
  require_once(RAWW_APP_CONFIG.'bootstrap.php');
  
  Dispatcher::delegate();