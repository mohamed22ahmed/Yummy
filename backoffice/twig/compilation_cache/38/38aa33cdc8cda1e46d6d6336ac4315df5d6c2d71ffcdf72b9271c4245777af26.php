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

/* print_order.html */
class __TwigTemplate_918383cef99f52921d100e631c06767ac4797c2179134bea873ef9e496e31fc8 extends Template
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
        echo "<html>
<body>  

<table class=\"collapse\" style=\"width:100%;\">
 <tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">           
    <img style=\"min-width:50px;height:50px;\" src=\"";
        // line 7
        echo twig_escape_filter($this->env, ($context["receipt_logo"] ?? null), "html", null, true);
        echo "\">
  </td>
 </tr>
 <tr>
   <td valign=\"middle\" align=\"center\" style=\"padding:30px;\">
    <h2 style=\"margin:0;\">";
        // line 12
        echo twig_escape_filter($this->env, ($context["receipt_thank_you"] ?? null), "html", null, true);
        echo "</h2>
   </td>
 </tr>
 
 <tr>
  <td style=\"background:#fef9ef;\">
   <table style=\"width:100%\" class=\"summary\">
     <tr>
      <td style=\"width:50%;\" valign=\"top\">        
        <table style=\"width:100%;\">
         <tr>
          <td colspan=\"2\"><h3>";
        // line 23
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "Summary", [], "any", false, false, false, 23), "html", null, true);
        echo "</h3></td>          
         </tr>
         <tr>
          <td style=\"width:35%;\" valign=\"top\">";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "order_no", [], "any", false, false, false, 26), "html", null, true);
        echo ":</td>
          <td valign=\"top\">";
        // line 27
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 27), "order_id", [], "any", false, false, false, 27), "html", null, true);
        echo "</td>
         </tr>
         <tr>
          <td style=\"width:35%;\" valign=\"top\">";
        // line 30
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "place_on", [], "any", false, false, false, 30), "html", null, true);
        echo ":</td>
          <td valign=\"top\">";
        // line 31
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 31), "place_on", [], "any", false, false, false, 31), "html", null, true);
        echo "</td>
         </tr>
         <tr>
          <td style=\"width:35%;\" valign=\"top\">";
        // line 34
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "order_total", [], "any", false, false, false, 34), "html", null, true);
        echo ":</td>
          <td valign=\"top\">";
        // line 35
        echo twig_escape_filter($this->env, ($context["total"] ?? null), "html", null, true);
        echo "</td>
         </tr>
         <tr>
          <td style=\"width:35%;\" valign=\"top\">";
        // line 38
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "delivery_date", [], "any", false, false, false, 38), "html", null, true);
        echo ":</td>
          <td valign=\"top\" >
           ";
        // line 40
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 40), "whento_deliver", [], "any", false, false, false, 40) == "now")) {
            // line 41
            echo "\t\t   ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 41), "schedule_at", [], "any", false, false, false, 41), "html", null, true);
            echo "
\t\t   ";
        } else {
            // line 43
            echo "\t\t   ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 43), "schedule_at", [], "any", false, false, false, 43), "html", null, true);
            echo "
\t\t   ";
        }
        // line 45
        echo "          </td>
         </tr>
        </table>
      </td>
      <td style=\"width:50%;\" valign=\"top\">       
        <table style=\"width:100%;\">
         <tr>
          <td colspan=\"2\"><h3>";
        // line 52
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "delivery_address", [], "any", false, false, false, 52), "html", null, true);
        echo "</h3></td>          
         </tr>
         <tr><td>";
        // line 54
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 54), "customer_name", [], "any", false, false, false, 54), "html", null, true);
        echo "</td></tr>
         <tr><td>";
        // line 55
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 55), "contact_number", [], "any", false, false, false, 55), "html", null, true);
        echo "</td></tr>
         <tr><td>";
        // line 56
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["order"] ?? null), "order_info", [], "any", false, false, false, 56), "delivery_address", [], "any", false, false, false, 56), "html", null, true);
        echo "</td></tr>
        </table>
      </td>
     </tr>
   </table>   
  </td>
 </tr>
 
 <tr>
   <td>
     <table style=\"width:100%\"  class=\"items\" >
     <thead>
     <tr>
      <td style=\"width:50%;\">";
        // line 69
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "items_ordered", [], "any", false, false, false, 69), "html", null, true);
        echo "</td>
      <td style=\"width:30%;\">";
        // line 70
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "qty", [], "any", false, false, false, 70), "html", null, true);
        echo "</td>
      <td style=\"width:20%;\">";
        // line 71
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "price", [], "any", false, false, false, 71), "html", null, true);
        echo "</td>
     </tr>
     </thead>
     <tr>
      <td colspan=\"3\" style=\"padding:0;\"><div style=\"border-bottom:thin solid black;\"></div></td>
     </tr>
     
      ";
        // line 78
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 79
            echo "     <tr>
      <td>
      <b>";
            // line 81
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "item_name", [], "any", false, false, false, 81), "html", null, true);
            echo "</b>
      ";
            // line 82
            if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 82), "size_name", [], "any", false, false, false, 82)) {
                // line 83
                echo "      (";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 83), "size_name", [], "any", false, false, false, 83), "html", null, true);
                echo ")
      ";
            }
            // line 85
            echo "      
       <br/>
      
      ";
            // line 88
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 88), "discount", [], "any", false, false, false, 88) > 0)) {
                // line 89
                echo "        <del>";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 89), "pretty_price", [], "any", false, false, false, 89), "html", null, true);
                echo "</del> ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 89), "pretty_price_after_discount", [], "any", false, false, false, 89), "html", null, true);
                echo "
      ";
            } else {
                // line 91
                echo "         ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 91), "pretty_price", [], "any", false, false, false, 91), "html", null, true);
                echo "
      ";
            }
            // line 93
            echo "      
       ";
            // line 94
            if ((twig_get_attribute($this->env, $this->source, $context["item"], "item_changes", [], "any", false, false, false, 94) == "replacement")) {
                // line 95
                echo "       <br>Replacement
       <br/>Replace \"";
                // line 96
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "item_name_replace", [], "any", false, false, false, 96), "html", null, true);
                echo "\"      
      ";
            }
            // line 98
            echo "      
      ";
            // line 99
            if (twig_get_attribute($this->env, $this->source, $context["item"], "special_instructions", [], "any", false, false, false, 99)) {
                // line 100
                echo "       ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "special_instructions", [], "any", false, false, false, 100), "html", null, true);
                echo "
      ";
            }
            // line 102
            echo "      
      ";
            // line 103
            if (twig_get_attribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, false, 103)) {
                // line 104
                echo "      <br/>
         ";
                // line 105
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, false, 105));
                foreach ($context['_seq'] as $context["attributes_key"] => $context["attributes"]) {
                    // line 106
                    echo "            ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["attributes"]);
                    foreach ($context['_seq'] as $context["attributes_index"] => $context["attributes_data"]) {
                        // line 107
                        echo "              ";
                        echo twig_escape_filter($this->env, $context["attributes_data"], "html", null, true);
                        echo "
                 ";
                        // line 108
                        if (($context["attributes_index"] < (twig_get_attribute($this->env, $this->source, $context["attributes"], "length", [], "any", false, false, false, 108) - 1))) {
                            // line 109
                            echo "                 ,
                 ";
                        }
                        // line 111
                        echo "            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['attributes_index'], $context['attributes_data'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    echo " 
         ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['attributes_key'], $context['attributes'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 113
                echo "      ";
            }
            // line 114
            echo "      
      </td>
      <td style=\"padding:0 20px 0;\">";
            // line 116
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "qty", [], "any", false, false, false, 116), "html", null, true);
            echo "</td>
      <td>
       ";
            // line 118
            if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 118), "discount", [], "any", false, false, false, 118) > 0)) {
                // line 119
                echo "        ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 119), "pretty_total_after_discount", [], "any", false, false, false, 119), "html", null, true);
                echo "
      ";
            } else {
                // line 121
                echo "        ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "price", [], "any", false, false, false, 121), "pretty_total", [], "any", false, false, false, 121), "html", null, true);
                echo "
      ";
            }
            // line 123
            echo "      </td>
     </tr>      
     
    <!--ADDON-->
    ";
            // line 127
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["item"], "addons", [], "any", false, false, false, 127));
            foreach ($context['_seq'] as $context["index_addon"] => $context["addons"]) {
                // line 128
                echo "    <tr>
     <td colspan=\"3\" style=\"padding:0 8px 0;\"><b>";
                // line 129
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["addons"], "subcategory_name", [], "any", false, false, false, 129), "html", null, true);
                echo "</b></td>     
    </tr>
        
    ";
                // line 132
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["addons"], "addon_items", [], "any", false, false, false, 132));
                foreach ($context['_seq'] as $context["_key"] => $context["addon_items"]) {
                    // line 133
                    echo "    <tr>
     <td>";
                    // line 134
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["addon_items"], "pretty_price", [], "any", false, false, false, 134), "html", null, true);
                    echo " ";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["addon_items"], "sub_item_name", [], "any", false, false, false, 134), "html", null, true);
                    echo "</td>
     <td style=\"padding:0 20px 0;\">";
                    // line 135
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["addon_items"], "qty", [], "any", false, false, false, 135), "html", null, true);
                    echo "</td>
     <td>";
                    // line 136
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["addon_items"], "pretty_addons_total", [], "any", false, false, false, 136), "html", null, true);
                    echo "</td>
    </tr>
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['addon_items'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 139
                echo "    
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['index_addon'], $context['addons'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 141
            echo "    <!--ADDON-->
    
     <!-- ADDITIONAL CHARGE -->      
    ";
            // line 144
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["item"], "additional_charge_list", [], "any", false, false, false, 144));
            foreach ($context['_seq'] as $context["_key"] => $context["item_charge"]) {
                // line 145
                echo "    <tr>
     <td><i>";
                // line 146
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item_charge"], "charge_name", [], "any", false, false, false, 146), "html", null, true);
                echo "</i></td>
     <td></td>
     <td>";
                // line 148
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item_charge"], "pretty_price", [], "any", false, false, false, 148), "html", null, true);
                echo "</td>
    </tr>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item_charge'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 151
            echo "    <!-- ADDITIONAL CHARGE -->  
    
     
     ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 154
        echo " <!--ITEMS-->
     
     <tr>
      <td colspan=\"3\" style=\"padding:0;\"><div style=\"border-bottom:thin solid black;\"></div></td>
     </tr> 
    
     <!--SUMMARY-->    
    ";
        // line 161
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($context["summary"]);
        foreach ($context['_seq'] as $context["_key"] => $context["summary"]) {
            echo "    
     <tr class=\"summary_order\">
      <td></td>
      <td style=\"padding:0 20px 0;\">
      
          ";
            // line 166
            if ((twig_get_attribute($this->env, $this->source, $context["summary"], "type", [], "any", false, false, false, 166) == "total")) {
                echo "  
\t       <b>";
                // line 167
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["summary"], "name", [], "any", false, false, false, 167), "html", null, true);
                echo " : </b>
\t      ";
            } else {
                // line 168
                echo "  
\t       ";
                // line 169
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["summary"], "name", [], "any", false, false, false, 169), "html", null, true);
                echo " :
\t      ";
            }
            // line 171
            echo "      
      </td>
      <td>
      
         ";
            // line 175
            if ((twig_get_attribute($this->env, $this->source, $context["summary"], "type", [], "any", false, false, false, 175) == "total")) {
                echo "  
\t     <b>";
                // line 176
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["summary"], "value", [], "any", false, false, false, 176), "html", null, true);
                echo "</b>
\t     ";
            } else {
                // line 177
                echo "  
\t     ";
                // line 178
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["summary"], "value", [], "any", false, false, false, 178), "html", null, true);
                echo "
\t     ";
            }
            // line 180
            echo "      
      </td>
     </tr> 
     ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['summary'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 183
        echo "      
     <!--SUMMARY-->      
    
     
     </table>
   </td>
 </tr>
  
 <tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">
    
    <table style=\"width:100%; table-layout: fixed;\">
\t  <tr>
\t    <th colspan=\"3\" style=\"text-align: left;\"><h5>";
        // line 196
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "contact_us", [], "any", false, false, false, 196), "html", null, true);
        echo "</h5></th>
\t    <th colspan=\"7\" style=\"text-align: left;\"><h5>";
        // line 197
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["label"] ?? null), "information", [], "any", false, false, false, 197), "html", null, true);
        echo "</h5></th>
\t  </tr>
\t  <tr>
\t    <td colspan=\"3\" style=\"text-align: left; padding:0 3px;\" valign=\"top\">
\t     <p>";
        // line 201
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "address", [], "any", false, false, false, 201), "html", null, true);
        echo "</p>
         <p>";
        // line 202
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "contact", [], "any", false, false, false, 202), "html", null, true);
        echo "</p>
         <p>";
        // line 203
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "email", [], "any", false, false, false, 203), "html", null, true);
        echo "</p
\t    </td>
\t    <td colspan=\"7\" valign=\"top\" style=\"padding:0 3px;\"><p>";
        // line 205
        echo twig_escape_filter($this->env, ($context["receipt_footer"] ?? null), "html", null, true);
        echo "</p></td>
\t  </tr>
\t</table>
  
  </td>
 </tr>
 
</table>

</body>
</html>";
    }

    public function getTemplateName()
    {
        return "print_order.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  503 => 205,  498 => 203,  494 => 202,  490 => 201,  483 => 197,  479 => 196,  464 => 183,  455 => 180,  450 => 178,  447 => 177,  442 => 176,  438 => 175,  432 => 171,  427 => 169,  424 => 168,  419 => 167,  415 => 166,  405 => 161,  396 => 154,  387 => 151,  378 => 148,  373 => 146,  370 => 145,  366 => 144,  361 => 141,  354 => 139,  345 => 136,  341 => 135,  335 => 134,  332 => 133,  328 => 132,  322 => 129,  319 => 128,  315 => 127,  309 => 123,  303 => 121,  297 => 119,  295 => 118,  290 => 116,  286 => 114,  283 => 113,  271 => 111,  267 => 109,  265 => 108,  260 => 107,  255 => 106,  251 => 105,  248 => 104,  246 => 103,  243 => 102,  237 => 100,  235 => 99,  232 => 98,  227 => 96,  224 => 95,  222 => 94,  219 => 93,  213 => 91,  205 => 89,  203 => 88,  198 => 85,  192 => 83,  190 => 82,  186 => 81,  182 => 79,  178 => 78,  168 => 71,  164 => 70,  160 => 69,  144 => 56,  140 => 55,  136 => 54,  131 => 52,  122 => 45,  116 => 43,  110 => 41,  108 => 40,  103 => 38,  97 => 35,  93 => 34,  87 => 31,  83 => 30,  77 => 27,  73 => 26,  67 => 23,  53 => 12,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<html>
<body>  

<table class=\"collapse\" style=\"width:100%;\">
 <tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">           
    <img style=\"min-width:50px;height:50px;\" src=\"{{receipt_logo}}\">
  </td>
 </tr>
 <tr>
   <td valign=\"middle\" align=\"center\" style=\"padding:30px;\">
    <h2 style=\"margin:0;\">{{receipt_thank_you}}</h2>
   </td>
 </tr>
 
 <tr>
  <td style=\"background:#fef9ef;\">
   <table style=\"width:100%\" class=\"summary\">
     <tr>
      <td style=\"width:50%;\" valign=\"top\">        
        <table style=\"width:100%;\">
         <tr>
          <td colspan=\"2\"><h3>{{label.Summary}}</h3></td>          
         </tr>
         <tr>
          <td style=\"width:35%;\" valign=\"top\">{{label.order_no}}:</td>
          <td valign=\"top\">{{order.order_info.order_id}}</td>
         </tr>
         <tr>
          <td style=\"width:35%;\" valign=\"top\">{{label.place_on}}:</td>
          <td valign=\"top\">{{order.order_info.place_on}}</td>
         </tr>
         <tr>
          <td style=\"width:35%;\" valign=\"top\">{{label.order_total}}:</td>
          <td valign=\"top\">{{total}}</td>
         </tr>
         <tr>
          <td style=\"width:35%;\" valign=\"top\">{{label.delivery_date}}:</td>
          <td valign=\"top\" >
           {% if order.order_info.whento_deliver=='now' %}
\t\t   {{order.order_info.schedule_at}}
\t\t   {% else %}
\t\t   {{order.order_info.schedule_at}}
\t\t   {% endif %}
          </td>
         </tr>
        </table>
      </td>
      <td style=\"width:50%;\" valign=\"top\">       
        <table style=\"width:100%;\">
         <tr>
          <td colspan=\"2\"><h3>{{label.delivery_address}}</h3></td>          
         </tr>
         <tr><td>{{order.order_info.customer_name}}</td></tr>
         <tr><td>{{order.order_info.contact_number}}</td></tr>
         <tr><td>{{order.order_info.delivery_address}}</td></tr>
        </table>
      </td>
     </tr>
   </table>   
  </td>
 </tr>
 
 <tr>
   <td>
     <table style=\"width:100%\"  class=\"items\" >
     <thead>
     <tr>
      <td style=\"width:50%;\">{{label.items_ordered}}</td>
      <td style=\"width:30%;\">{{label.qty}}</td>
      <td style=\"width:20%;\">{{label.price}}</td>
     </tr>
     </thead>
     <tr>
      <td colspan=\"3\" style=\"padding:0;\"><div style=\"border-bottom:thin solid black;\"></div></td>
     </tr>
     
      {% for item in items %}
     <tr>
      <td>
      <b>{{item.item_name}}</b>
      {% if item.price.size_name %}
      ({{item.price.size_name}})
      {% endif %}
      
       <br/>
      
      {% if item.price.discount>0 %}
        <del>{{item.price.pretty_price}}</del> {{item.price.pretty_price_after_discount}}
      {% else %}
         {{item.price.pretty_price}}
      {% endif %}
      
       {% if item.item_changes=='replacement' %}
       <br>Replacement
       <br/>Replace \"{{item.item_name_replace}}\"      
      {% endif %}
      
      {% if item.special_instructions %}
       {{item.special_instructions}}
      {% endif %}
      
      {% if item.attributes %}
      <br/>
         {% for attributes_key, attributes in item.attributes %}
            {% for attributes_index, attributes_data in attributes %}
              {{attributes_data}}
                 {% if attributes_index<(attributes.length-1) %}
                 ,
                 {% endif %}
            {% endfor %} 
         {% endfor %}
      {% endif %}
      
      </td>
      <td style=\"padding:0 20px 0;\">{{item.qty}}</td>
      <td>
       {% if item.price.discount>0 %}
        {{ item.price.pretty_total_after_discount }}
      {% else %}
        {{ item.price.pretty_total }}
      {% endif %}
      </td>
     </tr>      
     
    <!--ADDON-->
    {% for index_addon, addons in item.addons %}
    <tr>
     <td colspan=\"3\" style=\"padding:0 8px 0;\"><b>{{addons.subcategory_name}}</b></td>     
    </tr>
        
    {% for addon_items in addons.addon_items %}
    <tr>
     <td>{{addon_items.pretty_price}} {{addon_items.sub_item_name}}</td>
     <td style=\"padding:0 20px 0;\">{{addon_items.qty}}</td>
     <td>{{addon_items.pretty_addons_total}}</td>
    </tr>
    {% endfor %}
    
    {% endfor %}
    <!--ADDON-->
    
     <!-- ADDITIONAL CHARGE -->      
    {% for item_charge in item.additional_charge_list %}
    <tr>
     <td><i>{{item_charge.charge_name}}</i></td>
     <td></td>
     <td>{{item_charge.pretty_price}}</td>
    </tr>
    {% endfor %}
    <!-- ADDITIONAL CHARGE -->  
    
     
     {% endfor %} <!--ITEMS-->
     
     <tr>
      <td colspan=\"3\" style=\"padding:0;\"><div style=\"border-bottom:thin solid black;\"></div></td>
     </tr> 
    
     <!--SUMMARY-->    
    {% for summary in summary %}    
     <tr class=\"summary_order\">
      <td></td>
      <td style=\"padding:0 20px 0;\">
      
          {% if summary.type=='total'  %}  
\t       <b>{{summary.name}} : </b>
\t      {% else %}  
\t       {{summary.name}} :
\t      {% endif %}
      
      </td>
      <td>
      
         {% if summary.type=='total'  %}  
\t     <b>{{summary.value}}</b>
\t     {% else %}  
\t     {{summary.value}}
\t     {% endif %}
      
      </td>
     </tr> 
     {% endfor %}      
     <!--SUMMARY-->      
    
     
     </table>
   </td>
 </tr>
  
 <tr>
  <td style=\"background:#fef9ef;padding:20px 30px;\">
    
    <table style=\"width:100%; table-layout: fixed;\">
\t  <tr>
\t    <th colspan=\"3\" style=\"text-align: left;\"><h5>{{label.contact_us}}</h5></th>
\t    <th colspan=\"7\" style=\"text-align: left;\"><h5>{{label.information}}</h5></th>
\t  </tr>
\t  <tr>
\t    <td colspan=\"3\" style=\"text-align: left; padding:0 3px;\" valign=\"top\">
\t     <p>{{site.address}}</p>
         <p>{{site.contact}}</p>
         <p>{{site.email}}</p
\t    </td>
\t    <td colspan=\"7\" valign=\"top\" style=\"padding:0 3px;\"><p>{{receipt_footer}}</p></td>
\t  </tr>
\t</table>
  
  </td>
 </tr>
 
</table>

</body>
</html>", "print_order.html", "/home/mohamed/projects/yumv2/backoffice/twig/print_order.html");
    }
}
