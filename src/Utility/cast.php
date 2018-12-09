<?php
namespace p7g\Discord\Utility;

function cast(string $className, object $obj) {
  $class = new $className;
  foreach ($obj as $key => $value) {
    $class->$key = $value;
  }
  return $class;
}
