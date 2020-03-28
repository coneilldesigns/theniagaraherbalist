var templateUrl = page_data.templateUrl;
var url_base = templateUrl + '/menu_json.json?nocache=' + ( new Date() ).getTime();

function createTabs( categories ) {

	categories.unshift({
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
		if ( 'Concentrate Accessories' === cat.name || 'Flower' === cat.name || 'Vaporizers' === cat.name || 'Accessories' === cat.name || 'Grinders' === cat.name || 'Pipes and Bongs' === cat.name || 'Rolling Papers' === cat.name || 'Storage and Cleaning' === cat.name || 'Lighters' === cat.name ) {
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

			a.setAttribute( 'id', cat_name.toLowerCase() + '-tab' );
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

	var tbody = document.getElementById( 'products-body' );

	var productList = products.map( function( product ) {

		var row = document.createElement( 'tr' ); // Row with 4 coloumns
		var col1 = document.createElement( 'td' );
		var col1image = document.createElement( 'img' );
		var col1imagelink = document.createElement( 'a' );
		var col2 = document.createElement( 'td' );
		var col3 = document.createElement( 'td' );
		var col4 = document.createElement( 'td' );
		var col5 = document.createElement( 'td' );
		var col6 = document.createElement( 'td' );

		var priceConvert = ( product.price / 100 ).toFixed( 2 );

		// Setting data attr on first column for Name Sorting
		col1.setAttribute( 'data-sort', product.name.trim() );

		// Setting lightbox class and title on image
		col1imagelink.setAttribute( 'class', 'swipebox' );
		col1imagelink.setAttribute( 'title', product.name.trim() + ' - <b>$' + priceConvert + '</b>' );
		col1image.setAttribute( 'src', product.imageUrl );
		col1image.setAttribute( 'data-src', product.imageUrl );

		// Put everything together
		col1imagelink.appendChild( col1image );
		col1.appendChild( col1imagelink );
		row.appendChild( col1 );
		row.appendChild( col2 );
		row.appendChild( col3 );
		row.appendChild( col4 );
		row.appendChild( col5 );
		row.appendChild( col6 );
		tbody.appendChild( row );

		col1imagelink.innerHTML = '<img class="thumb" src="' + col1image.src + '" />' + product.name.trim();
		col2.innerHTML = product.categoryName.replace( '> > ', '' );

		if ( 'undefined' == typeof ( product.thc ) || null === product.thc || 0 === product.thc || '' === product.thc || '0' === product.thc ) {
			col3.innerHTML = '-';
		} else {
			col3.innerHTML = product.thc + '%';
		}

		if ( 'undefined' == typeof ( product.cbd ) || null === product.cbd || 0 === product.cbd || '' === product.cbd || '0' === product.cbd ) {
			col4.innerHTML = '-';
		} else {
			col4.innerHTML = product.cbd + '%';
		}
		col5.innerHTML = '$' + priceConvert;
		col6.innerHTML = product.description;
		col1imagelink.setAttribute( 'href', product.imageUrl );

	});

	var table = jQuery( '#product-table' ).DataTable({
		pageLength: 100,
		aaSorting: [
			[ 0, 'asc' ]
		],
		responsive: true,
		select: true,
		drawCallback: function( ) {
			jQuery( '#product-table img:visible' ).unveil();
			jQuery( '.swipebox' ).swipebox({
				hideBarsDelay: 0,
				removeBarsOnMobile: false
			});
		}
	});


	if ( -1 < window.location.href.indexOf( 'category' ) ) {
		jQuery.urlParam = function( name ) {
			var results = new RegExp( '[\?&]' + name + '=([^&#]*)' ).exec( window.location.href );
			return results[1] || 0;
		};

		var urlParam = jQuery.urlParam( 'category' );
		var urlParam = decodeURIComponent( urlParam );
		table.column( 1 ).search( urlParam, true, true );
		table.draw();
		var urlTabParam = decodeURIComponent( urlParam );
		var urlTabParam = urlTabParam.replace( / /g, '-' ).toLowerCase();

		$( '.menu-content a' ).removeClass( 'active' );
		$( '#' + urlTabParam + '-tab' ).addClass( 'active' );

	} else {
		table.column( 1 ).search( 'Sativa|Indica|Hybrid', true, true );
		table.draw();
	}


	table.on( 'page.dt', function() {
		jQuery( '#menu-scroll' ).animate({
			scrollTop: $( '.dataTables_wrapper' ).offset().top
		}, 'fast' );
	});

	table.on( 'click', 'tr', function() {
		jQuery( this ).toggleClass( 'selected' );
		console.log( table.rows( '.selected' ).data() );
	});

	jQuery( '#cart' ).click( function() {
		console.log( table.rows( '.selected' ).data() );
	});

	jQuery( '.nav-link' ).on( 'click', function() {
		jQuery( 'html, body, #menu-scroll' ).animate({
			scrollTop: 0
		}, 'fast' );

		if ( 'Dried Flower' === this.text ) {
			'Sativa|Indica|Hybrid' === this.text;
			table.column( 1 ).search( 'Sativa|Indica|Hybrid', true, true ).draw();
		} else {
			table.column( 1 ).search( this.text, true, true ).draw();
		}

	});

	jQuery( '.swipebox' ).swipebox({
		hideBarsDelay: 0,
		removeBarsOnMobile: false
	});

}

function fetchMenuJSON() {
	jQuery.ajaxSetup({ cache: false });

	jQuery.getJSON( url_base, function( json ) {
		console.log( json ); // this will show the info it in firebug console
		createTabs( json.categories );
		productListing( json.products );
	});
}


// jQuery( document ).ready( function( $ ) {
// 	fetchMenuJSON();
// });
