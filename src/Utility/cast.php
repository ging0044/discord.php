<?php
namespace p7g\Discord\Utility;

/**
 * Convert an object into a class instance by assigning all the object
 * properties to the same property on the class;
 *
 * @param string $className The name of the class to cast to
 * @param object $obj       The object to cast into the class
 * @return object An instance of the class passed in as $className
 */
function cast(string $className, object $obj) {
  $class = new $className;
  foreach ($obj as $key => $value) {
    $class->$key = $value;
  }
  return $class;
}
