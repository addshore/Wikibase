import MessageKeys from '@/definitions/MessageKeys';
import ErrorSavingAssertUser from '@/presentation/components/ErrorSavingAssertUser.vue';
import Vuex from 'vuex';
import {
	createLocalVue,
	shallowMount,
} from '@vue/test-utils';
import { createTestStore } from '../../../util/store';
import { BridgeConfig } from '@/store/Application';

describe( 'ErrorSavingAssertUser', () => {
	const stopAssertingUserWhenSaving = jest.fn();
	const retrySave = jest.fn();
	const goBackFromErrorToReady = jest.fn();
	const localVue = createLocalVue();
	localVue.use( Vuex );
	const store = createTestStore( {
		actions: {
			stopAssertingUserWhenSaving,
			retrySave,
			goBackFromErrorToReady,
		},
		state: {
			config: { usePublish: false } as BridgeConfig,
		},
	} );

	it( 'matches the snapshot', () => {
		const $messages = {
			KEYS: MessageKeys,
			getText: jest.fn().mockReturnValue( "Some 'text" ),
		};

		const wrapper = shallowMount( ErrorSavingAssertUser, {
			propsData: {
				loginUrl: 'https://data-bridge.test/Login',
			},
			mocks: { $messages },
			store,
			localVue,
		} );

		expect( wrapper.element ).toMatchSnapshot();
	} );

	it( 'dispatches stopAssertingUserWhenSaving and retrySave when publish without logging in is clicked', async () => {
		const $messages = {
			KEYS: MessageKeys,
			getText: jest.fn().mockReturnValue( "Some 'text" ),
		};

		const wrapper = shallowMount( ErrorSavingAssertUser, {
			propsData: {
				loginUrl: 'https://data-bridge.test/Login',
			},
			mocks: { $messages },
			store,
			localVue,
		} );
		const button = wrapper.find( '.wb-db-error-saving-assertuser__proceed' );
		button.vm.$emit( 'click' );
		await localVue.nextTick();

		expect( stopAssertingUserWhenSaving ).toHaveBeenCalledTimes( 1 );
		expect( retrySave ).toHaveBeenCalledTimes( 1 );
	} );

	// from our point of view, login/app behave identically; difference is tested in browser tests
	it.each( [
		[ 'login' ],
		[ 'back' ],
	] )( 'goes back if the %s button is clicked', async ( buttonName: string ) => {
		const $messages = {
			KEYS: MessageKeys,
			getText: jest.fn().mockReturnValue( "Some 'text" ),
		};

		const wrapper = shallowMount( ErrorSavingAssertUser, {
			propsData: {
				loginUrl: 'https://data-bridge.test/Login',
			},
			mocks: { $messages },
			store,
			localVue,
		} );
		const button = wrapper.find( `.wb-db-error-saving-assertuser__${buttonName}` );
		button.vm.$emit( 'click' );
		await localVue.nextTick();

		expect( goBackFromErrorToReady ).toHaveBeenCalledTimes( 1 );
	} );

	it( 'renders the proceed button using the SAVING_ERROR_ASSERTUSER_SAVE message', () => {
		const saveMessage = 'Save without logging in';
		const messageGet = jest.fn(
			( key: string ) => {
				if ( key === MessageKeys.SAVING_ERROR_ASSERTUSER_SAVE ) {
					return saveMessage;
				}

				return '';
			},
		);

		const wrapper = shallowMount( ErrorSavingAssertUser, {
			propsData: {
				loginUrl: 'https://data-bridge.test/Login',
			},
			mocks: {
				$bridgeConfig: { usePublish: false },
				$messages: {
					KEYS: MessageKeys,
					getText: messageGet,
				},
			},
			store,
			localVue,
		} );
		const button = wrapper.find( '.wb-db-error-saving-assertuser__proceed' );

		expect( messageGet ).toHaveBeenCalledWith( MessageKeys.SAVING_ERROR_ASSERTUSER_SAVE );
		expect( button.props( 'message' ) ).toBe( saveMessage );
	} );

	it( 'renders the proceed button using the SAVING_ERROR_ASSERTUSER_PUBLISH message', () => {
		const publishMessage = 'Publish without logging in';
		const messageGet = jest.fn(
			( key: string ) => {
				if ( key === MessageKeys.SAVING_ERROR_ASSERTUSER_PUBLISH ) {
					return publishMessage;
				}

				return '';
			},
		);
		const localStore = createTestStore( {
			actions: {
				stopAssertingUserWhenSaving,
				retrySave,
				goBackFromErrorToReady,
			},
			state: {
				config: { usePublish: true } as BridgeConfig,
			},
		} );

		const wrapper = shallowMount( ErrorSavingAssertUser, {
			propsData: {
				loginUrl: 'https://data-bridge.test/Login',
			},
			mocks: {
				$messages: {
					KEYS: MessageKeys,
					getText: messageGet,
				},
			},
			store: localStore,
			localVue,
		} );
		const button = wrapper.find( '.wb-db-error-saving-assertuser__proceed' );

		expect( messageGet ).toHaveBeenCalledWith( MessageKeys.SAVING_ERROR_ASSERTUSER_PUBLISH );
		expect( button.props( 'message' ) ).toBe( publishMessage );
	} );

} );
