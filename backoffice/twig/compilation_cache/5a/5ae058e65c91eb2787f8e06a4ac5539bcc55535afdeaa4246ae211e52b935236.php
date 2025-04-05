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

/* __string_template__b183fe4f2a3f3f2b64c9b8b8a27011a056c89f6f7ffaa772e7a21ffee9e6163b */
class __TwigTemplate_a26df19bab1a46bea14fa4e9627f3ade95c9c017659a3641248a0d8e2dc82ea1 extends Template
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
        echo "Refund for your #";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["order_info"] ?? null), "order_id", [], "any", false, false, false, 1), "html", null, true);
    }

    public function getTemplateName()
    {
        return "__string_template__b183fe4f2a3f3f2b64c9b8b8a27011a056c89f6f7ffaa772e7a21ffee9e6163b";
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
        return new Source("Refund for your #{{order_info.order_id}}", "__string_template__b183fe4f2a3f3f2b64c9b8b8a27011a056c89f6f7ffaa772e7a21ffee9e6163b", "");
    }
}
