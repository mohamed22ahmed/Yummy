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

/* __string_template__dcbf2b188a3b5d837c0ddf7bf37446cb7a52fe0701d964e3f856a103a4504d48 */
class __TwigTemplate_68ecafd31b72d69181135a4b6ee9cddc66a47573e80c5852b1bbc824b2f8eab8 extends Template
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
        $this->loadTemplate("header.html", "__string_template__dcbf2b188a3b5d837c0ddf7bf37446cb7a52fe0701d964e3f856a103a4504d48", 1)->display($context);
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
   <td style=\"padding:30px;background:#ffffff;\" valign=\"middle\" align=\"left\">
    
    <p style=\"margin-bottom:10px;\">Hi ";
        // line 11
        echo twig_escape_filter($this->env, ($context["first_name"] ?? null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, ($context["last_name"] ?? null), "html", null, true);
        echo ",</p>
\t
\t<p>You've successfully signed up for a ";
        // line 13
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "title", [], "any", false, false, false, 13), "html", null, true);
        echo " account! You can use this next time you order through ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "title", [], "any", false, false, false, 13), "html", null, true);
        echo ",</p>\t\t
\t<p>and you’ll get the latest promos, news, and updates.</p>
\t
\t<div style=\"padding-top:20px;\">
\t<p>Thank You!</p>
\t<p>";
        // line 18
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "title", [], "any", false, false, false, 18), "html", null, true);
        echo " Team</p>
\t</div>
\t
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
\t     <p>";
        // line 37
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "address", [], "any", false, false, false, 37), "html", null, true);
        echo "</p>
         <p>";
        // line 38
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "contact", [], "any", false, false, false, 38), "html", null, true);
        echo "</p>
         <p>";
        // line 39
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "email", [], "any", false, false, false, 39), "html", null, true);
        echo "</p>
\t    </td><td colspan=\"7\" style=\"padding:0 3px;\" valign=\"top\">
\t    
\t      ";
        // line 42
        $this->loadTemplate("social_link.html", "__string_template__dcbf2b188a3b5d837c0ddf7bf37446cb7a52fe0701d964e3f856a103a4504d48", 42)->display($context);
        // line 43
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
        // line 60
        $this->loadTemplate("footer.html", "__string_template__dcbf2b188a3b5d837c0ddf7bf37446cb7a52fe0701d964e3f856a103a4504d48", 60)->display($context);
    }

    public function getTemplateName()
    {
        return "__string_template__dcbf2b188a3b5d837c0ddf7bf37446cb7a52fe0701d964e3f856a103a4504d48";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  127 => 60,  108 => 43,  106 => 42,  100 => 39,  96 => 38,  92 => 37,  70 => 18,  60 => 13,  53 => 11,  44 => 5,  39 => 2,  37 => 1,);
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
   <td style=\"padding:30px;background:#ffffff;\" valign=\"middle\" align=\"left\">
    
    <p style=\"margin-bottom:10px;\">Hi {{first_name}} {{last_name}},</p>
\t
\t<p>You've successfully signed up for a {{site.title}} account! You can use this next time you order through {{site.title}},</p>\t\t
\t<p>and you’ll get the latest promos, news, and updates.</p>
\t
\t<div style=\"padding-top:20px;\">
\t<p>Thank You!</p>
\t<p>{{site.title}} Team</p>
\t</div>
\t
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
", "__string_template__dcbf2b188a3b5d837c0ddf7bf37446cb7a52fe0701d964e3f856a103a4504d48", "");
    }
}
