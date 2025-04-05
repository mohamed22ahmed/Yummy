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

/* __string_template__e926234cb7440c2c08da868095ca8f90dc5946dfe97bd1cddcc2a388a65fa111 */
class __TwigTemplate_635a985a286e7ba9efc35b74ee86884e3bef3108c0734b8a09a98e2f927365d3 extends Template
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
        $this->loadTemplate("header.html", "__string_template__e926234cb7440c2c08da868095ca8f90dc5946dfe97bd1cddcc2a388a65fa111", 1)->display($context);
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
   <td style=\"padding:30px;background:#ffffff;\" valign=\"middle\" align=\"center\">
    <h2 style=\"margin:0;\">Order is on the way!<br></h2>
    <p style=\"padding:10px;\">For everyone safety is our priority so remember to wash your hands before and after receiving your order<br></p>
    <br>    
    <a href=\"";
        // line 16
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 16), "tracking_link", [], "any", false, false, false, 16), "html", null, true);
        echo "\" target=\"_blank\" style=\"display:block;margin:auto;max-width:200px;padding:10px;background:#3ecf8e;color:#fff;
     text-decoration:none;font-size:18px;font-weight:bold;\">
     Track Order
     </a>
    
   </td>
 </tr>
 
 <tr>
  <td style=\"background:#fef9ef;\">
      ";
        // line 26
        $this->loadTemplate("summary.html", "__string_template__e926234cb7440c2c08da868095ca8f90dc5946dfe97bd1cddcc2a388a65fa111", 26)->display($context);
        // line 27
        echo "  </td>
 </tr>
 
 <tr>
   <td style=\"background:#ffffff;\">
     ";
        // line 32
        $this->loadTemplate("items.html", "__string_template__e926234cb7440c2c08da868095ca8f90dc5946dfe97bd1cddcc2a388a65fa111", 32)->display($context);
        // line 33
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
        // line 46
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "address", [], "any", false, false, false, 46), "html", null, true);
        echo "</p>
         <p>";
        // line 47
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "contact", [], "any", false, false, false, 47), "html", null, true);
        echo "</p>
         <p>";
        // line 48
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "email", [], "any", false, false, false, 48), "html", null, true);
        echo "</p>
\t    </td><td colspan=\"7\" style=\"padding:0 3px;\" valign=\"top\">
\t    
\t    ";
        // line 51
        $this->loadTemplate("social_link.html", "__string_template__e926234cb7440c2c08da868095ca8f90dc5946dfe97bd1cddcc2a388a65fa111", 51)->display($context);
        // line 52
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
        // line 70
        $this->loadTemplate("footer.html", "__string_template__e926234cb7440c2c08da868095ca8f90dc5946dfe97bd1cddcc2a388a65fa111", 70)->display($context);
    }

    public function getTemplateName()
    {
        return "__string_template__e926234cb7440c2c08da868095ca8f90dc5946dfe97bd1cddcc2a388a65fa111";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 70,  113 => 52,  111 => 51,  105 => 48,  101 => 47,  97 => 46,  82 => 33,  80 => 32,  73 => 27,  71 => 26,  58 => 16,  47 => 8,  39 => 2,  37 => 1,);
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
   <td style=\"padding:30px;background:#ffffff;\" valign=\"middle\" align=\"center\">
    <h2 style=\"margin:0;\">Order is on the way!<br></h2>
    <p style=\"padding:10px;\">For everyone safety is our priority so remember to wash your hands before and after receiving your order<br></p>
    <br>    
    <a href=\"{{order.order_info.tracking_link}}\" target=\"_blank\" style=\"display:block;margin:auto;max-width:200px;padding:10px;background:#3ecf8e;color:#fff;
     text-decoration:none;font-size:18px;font-weight:bold;\">
     Track Order
     </a>
    
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

{% include 'footer.html' %}", "__string_template__e926234cb7440c2c08da868095ca8f90dc5946dfe97bd1cddcc2a388a65fa111", "");
    }
}
