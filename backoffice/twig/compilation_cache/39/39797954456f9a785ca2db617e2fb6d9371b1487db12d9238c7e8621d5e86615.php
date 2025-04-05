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

/* __string_template__bda48fe30efc510fa9d0edcfbbb3b017828d7f629e265b1e823a8c06553ee6d0 */
class __TwigTemplate_f4d078b6bcad56891df2a50029b8025a1530cd76c433ccfeb01170a5b4871624 extends Template
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
        echo "Partial refund for your #";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["order_info"] ?? null), "order_id", [], "any", false, false, false, 1), "html", null, true);
    }

    public function getTemplateName()
    {
        return "__string_template__bda48fe30efc510fa9d0edcfbbb3b017828d7f629e265b1e823a8c06553ee6d0";
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
        return new Source("Partial refund for your #{{order_info.order_id}}", "__string_template__bda48fe30efc510fa9d0edcfbbb3b017828d7f629e265b1e823a8c06553ee6d0", "");
    }
}
