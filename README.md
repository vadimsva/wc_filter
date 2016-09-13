# wc_filter
Woocommerce filter plugin 

<br>

<h3>About:</h3>
wc_filter is plugin for filter woocoomerce products. Works much faster then other filter plugins. Flexible and easy to set up.
1. Support for multiple types: the standard list (checkboxes), drop-down list, a range of values, the color (or any type that can display an icon, such as a country)
2. Ability to specify the dimension for label, also can hide label
3. Set "or" and "and" query type for filtration of several parameters

<br><br>


<h3>How to install:</h3>

<b>1.</b> Add in <b>"header.php"</b>
<pre>
&lt;link rel="stylesheet" type="text/css" media="all" href="&lt;?php echo get_template_directory_uri(); ?&gt;/wc_filter.css" /&gt;
</pre>

<b>2.</b> Add in <b>"footer.php"</b>
<pre>
&lt;script src="&lt;?php echo get_template_directory_uri(); ?&gt;/wc_filter.js"&gt;&lt;/script&gt;
</pre>

<b>3.</b> Add in <b>"functions.php"</b>
<pre>include('wc_filter.php');</pre>

<b>4.</b> Add in <b>"woocommerce.php"</b>
<pre>
&lt;div id="filterToggle" class="button" style="display:none"&gt;Filter&lt;/div&gt;
&lt;div id="filterBlock"&gt;
	&lt;div class="block_caption"&gt;
		Filter
	&lt;/div&gt;
	&lt;div class="block_body"&gt;
		
		&lt;div id="wc_filter"&gt;
			&lt;?php
			echo wc_getProp('name=global_attr&query=or');
			/*
			name = 'global attribute slug'
			type = 'color', 'input', 'select', ''
			qyery = 'or', 'and'
			init = 'dimension for label, example "kg"'
			nolabel = 'true', '' - show/hide items name, useful for type = 'color'
			*/
			dynamic_sidebar('filter_price');
			?&gt;
			&lt;div class="wc_filter_buttons"&gt;
				&lt;button class="btn red" id="wc_filter_reset_but"&gt;Reset&lt;/button&gt;
				&lt;button class="btn" id="wc_filter_but"&gt;Filter&lt;/button&gt;
			&lt;/div&gt;
		&lt;/div&gt;
		
	&lt;/div&gt;
&lt;/div&gt;
</pre>

<b>5.</b> In Admin Panel, in Appearence -> Widgets, add in <b>"filter_price -> WooCommerce Filter by price"</b> and add in <b>"filter -> WooCommerce Layered Navigation"</b>
