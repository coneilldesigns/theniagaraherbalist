var templateUrl = page_data.templateUrl;
var url_base = templateUrl + '/menu_json.json?nocache=' + ( new Date() ).getTime();

function createTabs( categories ) {

	categories.unshift({
		id: 'c2d5bbd0-5127-41e1-ac16-6649d3c11885',
		name: 'All'
	}, {
		id: 'f3ef5cd5-d3c2-4054-9d60-a54fb7333da6',
		name: 'Dried Flower'
	}, {
		id: 'f3ef5cd5-d3c2-4054-9d60-a54fb7333da6',
		name: 'Sativa'
	}, {
		id: '3149b4ea-d6e0-4249-b495-a4ae7f08c85',
		name: 'Indica'
	}, {
		id: 'c2d5bbd0-5127-41e1-ac16-6649d3c11885',
		name: 'Hybrid'
	});

	var ul = document.getElementById( 'cat-tabs' );

	var listItems = categories.map( function( cat, index ) {
		if (
			'Flower' === cat.name ||
			'Accessories' === cat.name ||
			'Grinders' === cat.name ||
			'Pipes and Bongs' === cat.name ||
			'Rolling Papers' === cat.name ||
			'Storage and Cleaning' === cat.name ||
			'Lighters' === cat.name ||
			'Concentrate Accessories' === cat.name ||
			'Dried Flower' === cat.name ||
			'Capsules' === cat.name ||
			'Oils' === cat.name ||
			'Seeds' === cat.name ||
			'Edibles' === cat.name ||
			'Vaporizers' === cat.name ||
			'Batteries' === cat.name ||
			'Beverages' === cat.name ) {
			return false;
		} else {
			var li = document.createElement( 'li' );
			li.setAttribute( 'class', 'nav-item' );

			var a = document.createElement( 'a' );
			a.setAttribute( 'class', 'nav-link' );
			if ( 0 === index ) {
				a.setAttribute( 'class', 'nav-link active' );
			}

			var cat_name = cat.name.replace( / /g, '-' );

			a.setAttribute( 'id', cat_name.toLowerCase() );
			a.setAttribute( 'data-toggle', 'tab' );
			a.setAttribute( 'role', 'tab' );
			a.setAttribute( 'href', '#' + cat.name.toLowerCase() );


			li.appendChild( a );
			ul.appendChild( li );

			a.innerHTML = a.innerHTML + cat.name;
		}


	});


}

function productListing( products ) {

	const filteredProducts = [];

	products.map( function( product ) {

		if ( '> > Sativa' === product.categoryName || '> > Indica' === product.categoryName || '> > Hybrid' === product.categoryName || 'Vapes' === product.categoryName || 'Pre-Rolled' === product.categoryName ) {
			filteredProducts.push({
				product
			});
		}
	});

	console.log( 'FILTERED PRODUCTS', filteredProducts );

	const productArea = document.getElementById( 'products-body' );
	const productCardTemplate = document.querySelector( '.item-row' );
	const fragment = document.createDocumentFragment();

	filteredProducts.map( function( product, idx ) {

		const template = productCardTemplate.cloneNode( true );

		const categoryName = product.product.categoryName.replace( '> > ', '' ).toLowerCase();
		template.classList.add( categoryName );

		if ( 'Vapes' === product.product.categoryName || 'Pre-Rolled' === product.product.categoryName ) {
			template.classList.add( 'grey' );
		}

		let priceConvert = ( product.product.price / 100 ).toFixed( 2 );
		template.querySelector( '.vendor_name' ).innerText = product.product.supplierName;
		template.querySelector( '.product_name' ).innerText = product.product.name;
		template.querySelector( '.product_thc' ).innerText = product.product.thc + '%';
		template.querySelector( '.product_cbd' ).innerText = product.product.cbd + '%';
		template.querySelector( '.product_price' ).innerText = '$' + priceConvert;
		fragment.appendChild( template );

	});

	productArea.removeChild( productCardTemplate );
	productArea.appendChild( fragment );

}


function fetchMenuJSON() {
	jQuery.ajaxSetup({ cache: false });

	jQuery.getJSON( url_base, function( json ) {
		console.log( json ); // this will show the info it in firebug console
		createTabs( json.categories );
		productListing( json.products );
	});
}


jQuery( document ).ready( function( $ ) {
	fetchMenuJSON();

	$( 'body' ).on( 'click', '.nav-circles .nav-link', function() {
		if ( 'all' == this.id ) {
			jQuery( '#products-body > div' ).fadeIn( 450 );
		} else {
			let $el = jQuery( '.' + this.id ).fadeIn( 450 );
			jQuery( '#products-body > div' ).not( $el ).hide();
		}
	});

	setTimeout( function() {
		location.reload();
	}, 300000 );

});
