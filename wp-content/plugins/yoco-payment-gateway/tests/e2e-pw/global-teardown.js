const { chromium } = require( '@playwright/test' );
const { admin } = require( './test-data/data' );
const { exec } = require( 'child_process' );

module.exports = async ( config ) => {
	const { baseURL, subdomain, userAgent } = config.projects[ 0 ].use;

	// Specify user agent when running against an external test site to avoid getting HTTP 406 NOT ACCEPTABLE errors.
	const contextOptions = { baseURL, userAgent };

	const browser = await chromium.launch();
	const context = await browser.newContext( contextOptions );
	const adminPage = await context.newPage();

	let consumerTokenCleared = false;
	let tunnelCleared = false;

	console.log( "\n" );

	// Clean up the consumer keys
	const keysRetries = 5;
	for ( let i = 0; i < keysRetries; i++ ) {
		try {
			console.log( 'Trying to clear consumer token... Try: ' + i );
			await adminPage.goto( `/wp-admin` );
			await adminPage.fill( 'input[name="log"]', admin.username );
			await adminPage.fill( 'input[name="pwd"]', admin.password );
			await adminPage.click( 'text=Log In' );
			await adminPage.goto(
				`/wp-admin/admin.php?page=wc-settings&tab=advanced&section=keys`
			);
			await adminPage.dispatchEvent( 'a.submitdelete', 'click' );
			console.log( 'Cleared up consumer token successfully.' );
			consumerTokenCleared = true;
		} catch ( e ) {
			console.log( 'Failed to clear consumer token. Retrying...' );
		}

		try {
			console.log( 'Stopping tunnel...' );
			let command = "pkill -f '" + subdomain + "'";
			exec( command );
			tunnelCleared = true;
		} catch ( e ) {
			console.log( 'Can\t stop the tunnel' );
		}

		if ( consumerTokenCleared && tunnelCleared ) {
			break;
		}
	}

	if ( ! consumerTokenCleared ) {
		console.error( 'Could not clear consumer token.' );
		process.exit( 1 );
	}
};
