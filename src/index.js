/* eslint-disable */
/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	BaseControl,
	Button,
	ExternalLink,
	PanelBody,
	PanelRow,
	Placeholder,
	Spinner,
	ToggleControl
} = wp.components;

const {
	render,
	Component,
	Fragment
} = wp.element;

const { useSelect } = wp.data;

/**
 * Internal dependencies
 */
import './style.scss';

class App extends Component {
	constructor() {
		super( ...arguments );

		this.changeOptions = this.changeOptions.bind( this );

		this.state = {
			isAPILoaded: false,
			isAPISaving: false
		};

		this.products = [];
	}

	componentDidMount() {
		wp.api.loadPromise.then( () => {
			this.settings = new wp.api.models.Settings();

			if ( false === this.state.isAPILoaded ) {
				this.settings.fetch().then( response => {
					this.setState({
						isAPILoaded: true
					});
				});
			}
		});
	}

	displayProducts( page = 1 ) {
		// Querying products.
		const { products } = useSelect( ( select ) => {
			return select( 'core' ).getEntityRecords( 'postType', 'product' )
		} )

		console.log( products );

		// Populate rows for table.
		let rows = '';
		if( products ) {
			console.log(this.products);
			this.products.forEach( ( product ) => {
				console.log(product);
				rows += <tr><td>{ product.title }</td><td>{ product.thumbnail } </td><td>Edit</td></tr>
			});
		} else {
			rows = "Loading...";
			console.log(this.products);
		}
		return rows;
	}

	changeOptions( option, value ) {
		this.setState({ isAPISaving: true });

		const model = new wp.api.models.Settings({
			// eslint-disable-next-line camelcase
			[option]: value
		});

		model.save().then( response => {
			this.setState({
				[option]: response[option],
				isAPISaving: false
			});
		});
	}

	render() {
		if ( ! this.state.isAPILoaded ) {
			return (
				<Placeholder>
					<Spinner/>
				</Placeholder>
			);
		}

		// Display products table.
		return (
			<Fragment>
		 		<div className="elprod-header">
		 			<div className="elprod-container">
		 				<div className="elprod-logo">
		 					<h1>{ __( 'Products' ) }</h1>
		 				</div>
		 			</div>
		 		</div>
				<table className="widefat">{this.displayProducts()}</table>
			</Fragment>
		)
	}
}

render(
	<App/>,
	document.getElementById( 'elchallenge-products' )
);
