<?php
/**
 * Created by PhpStorm.
 * @author domenico domenico@translated.net / ostico@gmail.com
 * Date: 06/10/14
 * Time: 15.49
 *
 */

abstract class DataAccess_AbstractDaoSilentStruct extends DataAccess_AbstractDaoObjectStruct {
    protected $validator;
    protected $cached_results = array();

    /**
     * This method returns the same object so to be chainable
     * and be sure to clear the cache when calling cachable
     * methods.
     *
     * @example assuming the model has a cachable
     * method called foo();
     *
     * $model->foo(); // makes computation the first time and caches
     * $model->foo(); // returns the cached result
     * $model->clear()->foo(); // clears the cache and returns fresh data
     *
     * @return $this
     */
    public function clear() {
        $this->cached_results = array();
        return $this;
    }

    /**
     * This method makes it possible to define methods on child classes
     * whose result is cached on the instance.
     */
    protected function cachable($method_name, $params, $function) {
      if ( !key_exists($method_name,  $this->cached_results) ) {
        $this->cached_results[$method_name] =
          call_user_func($function, $params);
      }
      return $this->cached_results[$method_name];
    }

    public function __get( $name ) {
        if (!property_exists( $this, $name )) {
            throw new DomainException( 'Trying to get an undefined property ' . $name );
        }
    }

    public function __set( $name, $value ) {
        if ( !property_exists( $this, $name ) ) {
            // TODO: write to logs once we'll be able to have
            // distinct log levels. Should go in DEBUG level.
            // Log::doLog("DEBUG: Unknown property $name");
        }
    }

    public function toArray(){
        Log::doLog('DEPRECATED, use `attributes()` method instead');
        return $this->attributes();
    }

    public function attributes() {
        $refclass = new ReflectionClass( $this );
        $attrs = array();
        $publicProperties = $refclass->getProperties(ReflectionProperty::IS_PUBLIC) ;
        foreach( $publicProperties as $property ) {
            $attrs[$property->getName()] = $property->getValue($this);
        }
        return $attrs;
    }

}
