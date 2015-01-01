/*global _, Backbone, alert, wp, jQuery */
(function( $, window , Backbone, _ ) {

	"use strict";
	window.burningEars = window.burningEars || {};
	/**
	 * Inspired by Cocktail (https://github.com/onsi/cocktail/) but with some
	 * important modifications.
	 *
	 * Mixing an object into a class' prototype will make sure that object is
	 * extended from previous Mixins / oroginal prototype.
	 *
	 * Primitive values can also be mixed in.
	 *
	 * @param  {Object} object    The original object
	 * @param  {...Object} mixins Mixins
	 */
	window.WPSC.mixin = function( clss ) {
		var modules = _.rest( arguments );
		var chain = {};

		_.each( modules, function( module ) {
			var override = module._mixin_override || [];
			module = _.omit( module, [ '_mixin_override'] );

			_.each( module, function( value, key ) {
				if ( _.contains( override, key ) ) {
					chain[key] = [value];
					return;
				}

				if ( _.isFunction( value ) ) {
					if ( clss.prototype[key] )
						chain[key] = [clss.prototype[key]];

					chain[key].push( value );
				} else if ( _.isObject( value ) ) {
					chain[key] = chain[key] || [{}];
					if ( clss.prototype[key] )
						chain[key] = [clss.prototype[key]];

					chain[key].push( _.extend( {}, chain[key][0], value ) );
				} else {
					chain[key] = chain[key] || [];
					chain[key].push( value );
				}
			} );
		} );

		_.each( chain, function( values, key ) {
			var last = _.last( values );

			if ( ! _.isFunction( last ) ) {
				clss.prototype[key] = last;
				return;
			}

			clss.prototype[key] = function() {
				var ret, args = arguments, that = this;
				_.each( values, function( fn ) {
					var fnRet = fn.apply( that, args );
					ret =
						_.isUndefined( fnRet ) ?
						ret :
						fnRet;
				});

				return ret;
			};
		} );
	};

	var document = window.document;

	var burningEars = function() {

		var SELF = this;

		SELF.loadModal = function( e ) {
			e.preventDefault();
		};

		$( document ).ready( function() {
			$( '#misc-publishing-actions' ).on( 'click', '#notify-tweeters', SELF.loadModal );
		});

	};

	window.burningEars = new burningEars();

})( jQuery, window, Backbone, _ );