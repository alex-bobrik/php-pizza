<?php

interface ISearchable
{
    public function findAll();

    public function findById(int $id);
}