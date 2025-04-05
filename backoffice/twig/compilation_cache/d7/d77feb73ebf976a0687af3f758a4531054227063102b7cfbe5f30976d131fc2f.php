<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* __string_template__f6d732c241fc306948670e2c1ffa7453844cae4b9597265fa91d51acac6d8e3d */
class __TwigTemplate_b32b79c48d9c6b97ecb807bb80607f33082e5cfae432390aaeb740e220b868a3 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<p>Test runactions<br></p>";
    }

    public function getTemplateName()
    {
        return "__string_template__f6d732c241fc306948670e2c1ffa7453844cae4b9597265fa91d51acac6d8e3d";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<p>Test runactions<br></p>", "__string_template__f6d732c241fc306948670e2c1ffa7453844cae4b9597265fa91d51acac6d8e3d", "");
    }
}
