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

/* __string_template__dc5fce485a29e48068fd805283763fcc96c5eeeb906c2ae9623cfaa3d35c423a */
class __TwigTemplate_7d2922d76ce8d96fab52a43fb520c32368e273fdb6324fe5f1347e609873cdc5 extends Template
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
        $this->loadTemplate("header.html", "__string_template__dc5fce485a29e48068fd805283763fcc96c5eeeb906c2ae9623cfaa3d35c423a", 1)->display($context);
        // line 2
        echo "


<table style=\"width:100%;\">
 <tbody><tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">
    <img style=\"max-width:15%;max-height:50px;\" src=\"";
        // line 8
        echo twig_escape_filter($this->env, ($context["logo"] ?? null), "html", null, true);
        echo "\">
  </td>
 </tr>
 <tr>
   <td style=\"padding:30px;background:#ffffff;\" valign=\"middle\" align=\"left\">
   

    <p style=\"padding-bottom:15px\">Hi ";
        // line 15
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["order_info"] ?? null), "customer_name", [], "any", false, false, false, 15), "html", null, true);
        echo ",</p>
\t<p style=\"line-height:20px;\">
\tGood News! We’ve processed your full refund of ";
        // line 17
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["additional_data"] ?? null), "refund_amount", [], "any", false, false, false, 17), "html", null, true);
        echo " for your item(s) from order #";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["order_info"] ?? null), "order_id", [], "any", false, false, false, 17), "html", null, true);
        echo ".
\t</p>
\t
\t<p style=\"line-height:20px;\">Reversal may take 1 to 2 billing cycles or 5 to 15 banking days for local credit cards, and up to 45 banking days for international credit and debit cards, depending on your bank's processing time.</p>
    
   </td>
 </tr>
 
 <tr>
  <td style=\"background:#fef9ef;\">
      ";
        // line 27
        $this->loadTemplate("summary.html", "__string_template__dc5fce485a29e48068fd805283763fcc96c5eeeb906c2ae9623cfaa3d35c423a", 27)->display($context);
        // line 28
        echo "  </td>
 </tr>
 
 <tr>
   <td style=\"background:#ffffff;\">
     ";
        // line 33
        $this->loadTemplate("items.html", "__string_template__dc5fce485a29e48068fd805283763fcc96c5eeeb906c2ae9623cfaa3d35c423a", 33)->display($context);
        // line 34
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
        // line 47
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "address", [], "any", false, false, false, 47), "html", null, true);
        echo "</p>
         <p>";
        // line 48
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "contact", [], "any", false, false, false, 48), "html", null, true);
        echo "</p>
         <p>";
        // line 49
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "email", [], "any", false, false, false, 49), "html", null, true);
        echo "</p>
\t    </td><td colspan=\"7\" style=\"padding:0 3px;\" valign=\"top\">
\t    
\t    ";
        // line 52
        $this->loadTemplate("social_link.html", "__string_template__dc5fce485a29e48068fd805283763fcc96c5eeeb906c2ae9623cfaa3d35c423a", 52)->display($context);
        // line 53
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
        // line 71
        $this->loadTemplate("footer.html", "__string_template__dc5fce485a29e48068fd805283763fcc96c5eeeb906c2ae9623cfaa3d35c423a", 71)->display($context);
    }

    public function getTemplateName()
    {
        return "__string_template__dc5fce485a29e48068fd805283763fcc96c5eeeb906c2ae9623cfaa3d35c423a";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  139 => 71,  119 => 53,  117 => 52,  111 => 49,  107 => 48,  103 => 47,  88 => 34,  86 => 33,  79 => 28,  77 => 27,  62 => 17,  57 => 15,  47 => 8,  39 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% include 'header.html' %}



<table style=\"width:100%;\">
 <tbody><tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">
    <img style=\"max-width:15%;max-height:50px;\" src=\"{{logo}}\">
  </td>
 </tr>
 <tr>
   <td style=\"padding:30px;background:#ffffff;\" valign=\"middle\" align=\"left\">
   

    <p style=\"padding-bottom:15px\">Hi {{order_info.customer_name}},</p>
\t<p style=\"line-height:20px;\">
\tGood News! We’ve processed your full refund of {{additional_data.refund_amount}} for your item(s) from order #{{order_info.order_id}}.
\t</p>
\t
\t<p style=\"line-height:20px;\">Reversal may take 1 to 2 billing cycles or 5 to 15 banking days for local credit cards, and up to 45 banking days for international credit and debit cards, depending on your bank's processing time.</p>
    
   </td>
 </tr>
 
 <tr>
  <td style=\"background:#fef9ef;\">
      {% include 'summary.html' %}
  </td>
 </tr>
 
 <tr>
   <td style=\"background:#ffffff;\">
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
\t    {% include 'social_link.html' %}
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

{% include 'footer.html' %}", "__string_template__dc5fce485a29e48068fd805283763fcc96c5eeeb906c2ae9623cfaa3d35c423a", "");
    }
}
