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

/* __string_template__666c2eea373d704f1f68f79a4c3926d8fc6d478e48ad01757042c2443efa6f8e */
class __TwigTemplate_ab1786115a4a6911e9059d0a17b646336c5613b1e181e0c6e59c513e871b0809 extends Template
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
        $this->loadTemplate("header.html", "__string_template__666c2eea373d704f1f68f79a4c3926d8fc6d478e48ad01757042c2443efa6f8e", 1)->display($context);
        // line 2
        echo "<table style=\"width:100%;\">
 <tbody><tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">
    <img style=\"max-width:20%;max-height:50px;\" src=\"";
        // line 5
        echo twig_escape_filter($this->env, ($context["logo"] ?? null), "html", null, true);
        echo "\">
  </td>
 </tr> 
 <tr>
  <td style=\"background:#ffffff;\">
  
    ";
        // line 11
        $this->loadTemplate("summary.html", "__string_template__666c2eea373d704f1f68f79a4c3926d8fc6d478e48ad01757042c2443efa6f8e", 11)->display($context);
        // line 12
        echo "   
  </td>
 </tr>
 
 <tr>
   <td>
     ";
        // line 18
        $this->loadTemplate("items.html", "__string_template__666c2eea373d704f1f68f79a4c3926d8fc6d478e48ad01757042c2443efa6f8e", 18)->display($context);
        // line 19
        echo "   </td>
 </tr>
  
 <tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">
    
   <table style=\"width:100%; table-layout: fixed;\">
\t  <tbody><tr>
\t    <th colspan=\"3\" style=\"text-align: left;\"><h5>Contact Us</h5></th>
\t    <th colspan=\"7\" style=\"text-align: left;\"><h5>For  promos, news, and updates, follow us on:</h5></th>
\t  </tr>
\t  <tr>
\t    <td colspan=\"3\" style=\"text-align: left; padding:0 3px;\" valign=\"top\">
\t     <p>";
        // line 32
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "address", [], "any", false, false, false, 32), "html", null, true);
        echo "</p>
         <p>";
        // line 33
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "contact", [], "any", false, false, false, 33), "html", null, true);
        echo "</p>
         <p>";
        // line 34
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "email", [], "any", false, false, false, 34), "html", null, true);
        echo "</p>
\t    </td><td colspan=\"7\" style=\"padding:0 3px;\" valign=\"top\">
\t    
\t      ";
        // line 37
        $this->loadTemplate("social_link.html", "__string_template__666c2eea373d704f1f68f79a4c3926d8fc6d478e48ad01757042c2443efa6f8e", 37)->display($context);
        // line 38
        echo "\t     
\t     <table>
\t      <tbody><tr>
\t      <td style=\"padding:0;\"><a href=\"#\" style=\"color:#000;font-size:16px;\">Terms and Conditions</a></td>
\t      <td>●</td>
\t      <td style=\"padding:0;\"><a href=\"#\" style=\"color:#000;font-size:16px;\">Privacy Policy</a></td>
\t      </tr>
\t     </tbody></table>
\t    
\t    </td>
\t  </tr>
\t</tbody></table>
  
  </td>
 </tr>
 
</tbody></table>
";
        // line 55
        $this->loadTemplate("footer.html", "__string_template__666c2eea373d704f1f68f79a4c3926d8fc6d478e48ad01757042c2443efa6f8e", 55)->display($context);
    }

    public function getTemplateName()
    {
        return "__string_template__666c2eea373d704f1f68f79a4c3926d8fc6d478e48ad01757042c2443efa6f8e";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  115 => 55,  96 => 38,  94 => 37,  88 => 34,  84 => 33,  80 => 32,  65 => 19,  63 => 18,  55 => 12,  53 => 11,  44 => 5,  39 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% include 'header.html' %}
<table style=\"width:100%;\">
 <tbody><tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">
    <img style=\"max-width:20%;max-height:50px;\" src=\"{{logo}}\">
  </td>
 </tr> 
 <tr>
  <td style=\"background:#ffffff;\">
  
    {% include 'summary.html' %}
   
  </td>
 </tr>
 
 <tr>
   <td>
     {% include 'items.html' %}
   </td>
 </tr>
  
 <tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">
    
   <table style=\"width:100%; table-layout: fixed;\">
\t  <tbody><tr>
\t    <th colspan=\"3\" style=\"text-align: left;\"><h5>Contact Us</h5></th>
\t    <th colspan=\"7\" style=\"text-align: left;\"><h5>For  promos, news, and updates, follow us on:</h5></th>
\t  </tr>
\t  <tr>
\t    <td colspan=\"3\" style=\"text-align: left; padding:0 3px;\" valign=\"top\">
\t     <p>{{site.address}}</p>
         <p>{{site.contact}}</p>
         <p>{{site.email}}</p>
\t    </td><td colspan=\"7\" style=\"padding:0 3px;\" valign=\"top\">
\t    
\t      {% include 'social_link.html' %}
\t     
\t     <table>
\t      <tbody><tr>
\t      <td style=\"padding:0;\"><a href=\"#\" style=\"color:#000;font-size:16px;\">Terms and Conditions</a></td>
\t      <td>●</td>
\t      <td style=\"padding:0;\"><a href=\"#\" style=\"color:#000;font-size:16px;\">Privacy Policy</a></td>
\t      </tr>
\t     </tbody></table>
\t    
\t    </td>
\t  </tr>
\t</tbody></table>
  
  </td>
 </tr>
 
</tbody></table>
{% include 'footer.html' %}
", "__string_template__666c2eea373d704f1f68f79a4c3926d8fc6d478e48ad01757042c2443efa6f8e", "");
    }
}
