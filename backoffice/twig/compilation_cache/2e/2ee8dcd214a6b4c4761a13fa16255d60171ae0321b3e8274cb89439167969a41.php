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

/* __string_template__d471aa357bf3596539b4e067029fe24aa08a96475141b4b18700f4ad41ce85eb */
class __TwigTemplate_67a2e9832464c5b19e61ba4f026a9afbcad9fb9b09da53b6143b8eecc96daafd extends Template
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
        echo "New order #";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["order_info"] ?? null), "order_id", [], "any", false, false, false, 1), "html", null, true);
        echo " from ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["order_info"] ?? null), "customer_name", [], "any", false, false, false, 1), "html", null, true);
    }

    public function getTemplateName()
    {
        return "__string_template__d471aa357bf3596539b4e067029fe24aa08a96475141b4b18700f4ad41ce85eb";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("New order #{{order_info.order_id}} from {{order_info.customer_name}}", "__string_template__d471aa357bf3596539b4e067029fe24aa08a96475141b4b18700f4ad41ce85eb", "");
    }
}
