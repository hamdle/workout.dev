<?php

/*
 * Class Record
 *
 * A record from the database.
 * Models should extend this class and define these abstract functions.
 *
 * @author Eric Marty
 * @since 12-16-2023 1:16 PM
 */

namespace api\Core\Database;

abstract class Record
{
    use \api\Core\Traits\Messages;

    public $fields = [];
    protected $table;

    private $id;

    abstract public function fieldValidation();
    abstract public function databaseTransforms();

    public function __construct($fields = [], $table)
    {
        $this->fields = $fields;
        $this->table = $table;
    }

    public function __get($field)
    {
        return array_key_exists($field, $this->fields ?? [])
            ? $this->fields[$field]
            : null;
    }

    public function __set($field, $value)
    {
        $this->fields[$field] = $value;
    }

    // return = Id of inserted record | null
    public function save()
    {
        $this->transformFormFields();
        $id = Database::insert(
            $this->table,
            array_keys($this->fields),
            array_values($this->fields));

        return $this->fields["id"] = (is_numeric($id) ? $id : null);
    }

    public function loadFromDatabase()
    {
        $this->transformFormFields();
        $results = Database::select($this->table, "*", $this->fields);

        if (is_array($results) && array_key_exists(0, $results))
        {
            foreach ($results[0] as $key => $value)
            {
                $this->fields[$key] = $value;
            }
        }
        else
        {
            $this->messages[] = ucfirst($this->table)." not found.";
            return false;
        }

        return true;
    }

    public function validateFormFields()
    {
        $this->messages = [];
        foreach ($this->fieldValidation() as $key => $validator)
        {
            if (array_key_exists($key, $this->fields))
            {
                if (($validationResponse = $validator($this->fields[$key])) !== true)
                    $this->messages[$key] = $validationResponse;
            }
        }
        return empty($this->messages);
    }

    public function transformFormFields()
    {
        foreach ($this->fields as $key => $field)
        {
            if (!array_key_exists($key, $this->fieldValidation()))
                unset($this->fields[$key]);
        }
        foreach ($this->databaseTransforms() as $key => $transform)
        {
            if (array_key_exists($key, $this->fields))
                $this->fields[$key] = $transform($this->fields[$key]);
        }
    }
}
