<?php

namespace Untek\Lib\Web\Form\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

interface BuildFormInterface
{

    public function buildForm(FormBuilderInterface $formBuilder);
}
