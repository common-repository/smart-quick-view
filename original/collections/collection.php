<?php

namespace SmartQuickView\Original\Collections;

use BadMethodCallException;
use Closure;
use SmartQuickView\Original\Abilities\Invokable;
use SmartQuickView\Original\Characters\StringManager;
use SmartQuickView\Original\Collections\Abilities\ArrayRepresentation;
use SmartQuickView\Original\Collections\ArrayGetter;
use SmartQuickView\Original\Collections\Mapper\Types;
use JsonSerializable;

Class Collection implements ArrayRepresentation, JsonSerializable
{
    private $elements = [];
    protected $limitFilterElements = 0;
    protected $separator = ', ';

    public static function range($minimum, $maximum)
    {
        return new static(range($minimum, $maximum));   
    }

    public static function createFromString($stringRepresentation, $separator = null)
    {
        $separator = $separator?: ',';

        return new self(array_map(function($item) {
            return trim($item);
        }, explode($separator, $stringRepresentation)));
    }
    
    public static function create(/*Array|Collection*/ $elements)
    {
        return new static($elements ?? []);
    }
    
    
    public function __construct(/*Array|Collection*/ $elements)
    {
        (array) $elements = ArrayGetter::getArrayOrThrowExceptionFrom($elements ?? []);

        $this->elements = $elements;
    }

    public function asArray()
    {
        return $this->elements;
    }

    public function asJson()
    {
        return new StringManager(json_encode($this));   
    }

    public function asStringRepresentation()
    {
        return $this->implode($this->separator);   
    }

    public function clean()
    {
        return new static(array_filter($this->elements));   
    }

    public function resetKeys()
    {
        $this->elements = array_values($this->elements);
        return $this;   
    }
    

    public function push($element)
    {
        $this->elements[] = $element;

        return $this;
    }

    public function set($key, $value)
    {
        return $this->add($key, $value);   
    }

    public function add($key, $value)
    {
        $key = (string) $key;
        
        $this->elements[$key] = $value;   

        return $this;
    }

    public function append(/*Array|Colection*/ $elements)
    {   
        (array) $elements = ArrayGetter::getArrayOrThrowExceptionFrom($elements);

        foreach ($elements as $key => $value) {
            if (is_numeric($key)) {
                $this->elements[] = $value;
            } else {
                $this->elements[$key] = $value;
            }
        }

        return $this;
    }
    
    public function appendAsArray(/*Array|Colection*/ $elements)
    {
        (array) $elements = ArrayGetter::getArrayOrThrowExceptionFrom($elements);

        foreach ($elements as $key => $value) {
            if (isset($this->elements[$key])) {
                if (is_array($this->elements[$key])) {
                    $this->elements[$key][] = $value;
                } else {
                    $this->elements[$key] = [$this->elements[$key], $value];
                }
            } else {
                $this->elements[$key] = [$value];
            }
        }

        return $this;
    }
    
    public function concat($arrayOrCollection)
    {
        if ($arrayOrCollection instanceof Collection) {
            $newElements = $arrayOrCollection->asArray();
        } else {
            $newElements = $arrayOrCollection;
        }

        return new static(array_merge($this->elements, $newElements));
    }

    public function merge($arrayOrCollection)
    {
        return $this->concat($arrayOrCollection);   
    }

    public function mergeIf($condition, $arrayOrCollection)
    {
        if ($condition) {
            return $this->concat($arrayOrCollection); 
        }

        return $this;
    }
    
    public function first($numberOfItems = null)
    {
        if (!is_null($numberOfItems)) {
            (array) $elements = $this->elements; // clone the current array
            return new Collection(array_slice($elements, 0, $numberOfItems));
        }

        (boolean) $isFirstIteration = true;

        foreach ($this->elements as $key => $value) {
            if ($isFirstIteration) {
                return $value;
            }
        }
    }

    public function last()
    {
        return isset($this->elements[$this->lastKey()])? $this->elements[$this->lastKey()] : null;
    }

    public function haveMoreThan($number)
    {
        return count($this->elements) > $number;
    }

    public function haveLessThan($number)
    {
        return count($this->elements) < $number;
    }

    public function haveExactly($number)
    {
        return count($this->elements) === $number;
    }

    public function haveAtLeast($number)
    {
        return count($this->elements) >= $number;
    }

    public function haveMaximum($number)
    {
        return count($this->elements) <= $number;
    }

    public function haveAny()
    {
        return count($this->elements) > 0;
    }

    public function haveNone()
    {
        return !$this->haveAny();
    }

    public function count()
    {
        return count($this->elements);
    }

    public function isIndexed()
    {
        foreach ($this->elements as $key => $value) {
            if (is_string($key)) {
                return true;
            }
        }

        return false;
    }

    public function atPosition($index)
    {
        (integer) $currentindex = 1;

        foreach ($this->elements as $element) {
            if ($currentindex === $index) {
                return $element;
            } else {
                $currentindex++;
            }
        }
        
    }

    public function hasKey($keyToSearch)
    {
        $keyToSearch = (string) $keyToSearch;

        return isset($this->elements[$keyToSearch]);   
    }

    public function withoutDuplicates()
    {
        (object) $uniqueCollection = new static(array_unique($this->elements));

        return !$uniqueCollection->isIndexed()? $uniqueCollection->getValues() : $uniqueCollection;
    }
    
    public function search($value)
    {
        return array_search($value, $this->elements);   
    }

    public function map(Callable $callable)
    {
        return new static(array_map($callable, $this->elements));
    }

    public function mapWithKeys(Callable $callable)
    {
        (array) $newArray = [];

        foreach($this->elements as $index => $element) {
            (array) $mappedData = $callable($element, $index);
            $newArray[$mappedData['key']] = $mappedData['value'];
        }

        return new static($newArray);
    }

    /**
     * MUTABLE ITERATION, RETURNS THE SAME INSTANCE
     */
    public function forEvery(Callable $callable)
    {
        foreach ($this->elements as $key => &$value) {
            $result = $callable($value, $key);

            if ($result === false) {
                break;
            }
        }

        return $this;
    }

    public function reduce(Callable $callback, $initial = null)
    {
        $reduceResult = array_reduce($this->elements, $callback, $initial);

        return Types::isString($reduceResult)? new StringManager((string) $reduceResult) : $reduceResult;
    }

    public function reverse()
    {
        return new static(array_flip($this->asArray()));   
    }

    public function invert()
    {
        return new static(array_reverse($this->asArray()));   
    }
    
    public function asList($separator = ',')
    {
        return $this->implode("{$separator} ")->trim("{$separator} ");
    }

    public function implode($separator)
    {
        return new StringManager(implode($separator, $this->elements));   
    }

    public function filter(Callable $callable = null)
    {
        if (!is_callable($callable)) {
            return new static(array_filter($this->elements));
        }

        return $this->getFilteredElements($callable);
    }

    /**
     * Filters elements and removes the filtered elements from the original Collection instance.
     * 
     * @return Collection  A new Collection with the filtered items. The original collection gets the filtered elements     
     *                     permanently removed.
     */
    public function filterAndRemove(Callable $callable = null)
    {
        if (!is_callable($callable)) {
            return new static(array_filter($this->elements));
        }

        return $this->getFilteredElements(function($element, $key) use ($callable) {
            (boolean) $shouldBeFiltered = $callable instanceof Closure || $callable instanceof Invokable? $callable($element, $key) : $callable($element);

            if ($shouldBeFiltered) {
                $this->remove($key);
                return true;
            }

            return false;
        });
    }

    public function filterFirst($limit, Callable $callable)
    {
        return $this->getFilteredElements($callable, $limit);
    }

    public function find(Callable $callable)
    {
        return $this->getFilteredElements($callable, $limit = 1)->first();   
    }

    protected function getFilteredElements(Callable $callable, $limit = 0)
    {
        (object) $filteredElements = new static([]);
        (integer) $numberOfFilteredElements = 0;

        foreach ($this->elements as $key => $value) {
            if ($callable instanceof Closure || $callable instanceof Invokable) {
                (boolean) $canBeIncluded = $callable($value, $key);
            } else {
                (boolean) $canBeIncluded = $callable($value);
            }

            if ($canBeIncluded) {
                $filteredElements->add($key, $value);
                $numberOfFilteredElements++;
            }

            if ($limit > 0 && $numberOfFilteredElements === $limit) {
                break;
            }
        }

        return $filteredElements;   
    }

    public function shift()
    {
        return array_shift($this->elements);   
    }

    /**
     * Array_pop without modifying the original array and without passing the popped value
     * @return Collection
     */
    public function withoutLast()
    {
        (array) $newArray = $this->elements;

        array_pop($newArray);

        return new Collection($newArray);
    }
    
    /**
     * array_diff
     */
    public function not(/*ArrayRepresentation*/ $elements)
    {
        (array) $elements = ArrayGetter::getArrayOrThrowExceptionFrom($elements);

        return new static(array_diff($this->elements, $elements));
    }

    public function sort(Callable $callable)
    {
        (array) $newArray = $this->elements;

        usort($newArray, $callable);

        return new static($newArray);   
    }

    public function except(/*Array|Collection*/ $keysToExclude)
    {
        (array) $keysToExclude = ArrayGetter::getArrayOrThrowExceptionFrom($keysToExclude);

        return (new static($this->elements))->filter(function($value, $key) use($keysToExclude) {
            return !in_array($key, $keysToExclude);
        });
    }

    public function only(/*Array|Collection*/ $keysToInclude)
    {
        (array) $keysToInclude = ArrayGetter::getArrayOrThrowExceptionFrom($keysToInclude);

        return new static(
            array_intersect_key($this->elements, array_flip($keysToInclude))
        );
    }

    /**
     * Compares the inner elements array (self::$elements) to the given array,
     * both arrays must be equal; StringManager values will be typecasted to regular
     * strings so different instances with the same value will evaluate to true
     */
    public function are(Array $itemsToCompare)
    {
        if (count($itemsToCompare) !== $this->count()) {
            return false;
        }

        foreach ($itemsToCompare as $keyToCompare => $valueToCompare) {
            if (!$this->hasKey($keyToCompare)) {
                return false;
            }
            $selfValue = $this->get($keyToCompare);

            if ($valueToCompare instanceof StringManager) {
                $valueToCompare = $valueToCompare->get();
            }

            if ($selfValue instanceof StringManager) {
                $selfValue = $selfValue->get();
            }

            if ($selfValue !== $valueToCompare) {
                return false;
            }
        }

        return true;
    }
    public function areNot(Array $items)
    {
        return !$this->are($items);
    }
    
    public function contain($itemToSearch)
    {
        if ($itemToSearch instanceof Closure) {
            return $this->filter($itemToSearch)->haveAny();
        }

        if (Types::isString($itemToSearch)) {
            return in_array(strtolower($itemToSearch), $this->map(StringManager::convertToLowerCase())->asArray());
        }

        if (is_array($itemToSearch) || $itemToSearch instanceof Collection) {
            $itemToSearch = ArrayGetter::getArrayOrThrowExceptionFrom($itemToSearch);
            $itemToSearch = (new static($itemToSearch))
                            ->map(static::convertToString())
                            ->asArray();
        }

        return in_array(
            $itemToSearch, 
            $this->map(static::convertToString())
                 ->map(function($value){return ($value instanceof Collection)? $value->asArray() : $value;})
                 ->asArray(), 
            $strictTypeSearch = true
        );
    }

    public function containEither(Array $elements)
    {
        foreach($elements as $element) {
            if ($this->contain($element)) {
                return true;
            }
        }

        return false;
    }

    public function containAll(/*Colection|Array*/$elements)
    {

        if ($elements instanceof Collection) {
            $elements = $elements->asArray();
        }

        if (empty($elements) && $this->haveAny()) return false;

        foreach($elements as $element) {
            if (!$this->contain($element)) {
                return false;
            }
        }

        return true;  
    }
    
    public function allMatch($regularExpression)
    {
        if ($this->haveNone()) return false;

        foreach ($this->elements as $element) {
            if (is_string($element)) {
                $element = new StringManager($element);
            } elseif (!($element instanceof StringManager)) {
                return false;
            }
            if (!$element->matches($regularExpression)) {
                return false;
            }
        }

        return true;
    }
    
    public function intersect(...$collections) : Collection
    {
        if (empty($collections)) {
            // we'll intersect the current elements
            $collections = $this->elements;
        }



        $collections = array_map(function($item) {
            return ($item instanceof Collection)? $item->asArray() : $item;
        }, $collections);

        if (count($collections) < 2) {
            return new Collection((new Collection($collections))->first());
        }

        return new Collection(array_values(array_intersect(...$collections)));
    }
    
    public function test(Callable $callable)
    {
        foreach ($this->elements as $element) {
            (boolean) $hasItpassed = ($callable($element) === true);

            if ($hasItpassed) {
                return true;
            }
        }

        return false;
    }

    public function get($key)
    {
        $key = (string) $key;

        if ($this->hasKey($key)) {
            return $this->elements[$key];
        }   
    }

    public function remove($key)
    {
        $key = (string) $key;

        if ($this->hasKey($key)) {
            unset($this->elements[$key]);
        }

        return $this;
    }

    public function removeFirst()
    {
        $this->remove($this->firstKey());

        return $this;   
    }

    public function removelast()
    {
        $this->remove($this->lastKey());

        return $this;   
    }

    public function firstKey()
    {
        foreach ($this->elements as $key => $value) {
            return $key;
        }   
    }

    public function lastKey()
    {
        if (function_exists('array_key_last')) { // better performance on big arrays
            return array_key_last($this->elements);
        }

        (string) $lastestKey = null;

        foreach ($this->elements as $key => $value) {
            $lastestKey = $key;            
        }   

        return $lastestKey;
    }

    public function getKeys()
    {
        return (new static(array_keys($this->elements)))->map($this->valueToStringManager());   
    }
    
    public function getValues()
    {
        return new static(array_values($this->elements));   
    }
    

    public function getEarliest(Array $elementsToSearch)
    {
        return $this->getValueSortedBy(function($index, $validElement, $currentPosition) {
            return ($index < $currentPosition)? $index : false;
        }, $elementsToSearch);

    }

    public function getLatest(Array $elementsToSearch)
    {
        return $this->getValueSortedBy(function($index, $validElement, $currentPosition) {
            return ($index > $currentPosition)? $index : false;
        }, $elementsToSearch, $currentPosition = 0);
    }

    public function getByField($field, $value)
    {
        $key = array_search($value, array_column($this->elements, $field));

        if (($key !== false) && isset($this->elements[$key])) {
            return $this->elements[$key];
        }
    }

    public function setSeparator($separator)
    {
        if ($separator) {
            $this->separator = $separator;
        }
    }
    
    public static function areEqual($collectionOrArray1, $collectionOrArray2)
    {
        if (!($collectionOrArray1 instanceof Collection) && (!is_array($collectionOrArray1))) {
            return false;
        } elseif (!($collectionOrArray2 instanceof Collection) && (!is_array($collectionOrArray2))) {
            return false;
        }

        (array) $array1 = ArrayGetter::getArrayOrThrowExceptionFrom($collectionOrArray1);
        (array) $array2 = ArrayGetter::getArrayOrThrowExceptionFrom($collectionOrArray2);

        return $array1 === $array2;
    }
    

    public function jsonSerialize()
    {
        return $this->elements;   
    }
    
    protected function getValueSortedBy(Callable $sortType, Array $elementsToSearch, $currentPosition = 1000000)
    {
        (array) $validElements = array_intersect($this->elements, $elementsToSearch);

        foreach ($validElements as $index => $validElement) {
            $result = $sortType($index, $validElement, $currentPosition);

            if (is_int($result)) {
                $currentPosition = $result;
            }
        }

        return isset($this->elements[$currentPosition])? $this->elements[$currentPosition] : null;
    }

    protected function valueToStringManager()
    {
        return function($key){
            if (is_string($key)) {
                return new StringManager($key);
            }
            
            return $key;
        };   
    }

    public static function convertToString()
    {
        return function($value) {
            return StringManager::stringToNative($value);           
        };            
    }
}