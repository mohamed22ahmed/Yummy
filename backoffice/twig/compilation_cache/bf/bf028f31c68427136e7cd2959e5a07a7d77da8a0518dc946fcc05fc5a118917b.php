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

/* __string_template__4c648a32b067b715c060277dbbd39cba8313420cb921a78c3cf02051ff9a32b2 */
class __TwigTemplate_3056f42e54e39f359a6a6411fa0b2cf99c8e5d6ab4bf8d10b6fdb7d43628369e extends Template
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
        echo "Your order #";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["order_info"] ?? null), "order_id", [], "any", false, false, false, 1), "html", null, true);
        echo " is cancelled";
    }

    public function getTemplateName()
    {
        return "__string_template__4c648a32b067b715c060277dbbd39cba8313420cb921a78c3cf02051ff9a32b2";
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
        return new Source("Your order #{{order_info.order_id}} is cancelled", "__string_template__4c648a32b067b715c060277dbbd39cba8313420cb921a78c3cf02051ff9a32b2", "");
    }
}
