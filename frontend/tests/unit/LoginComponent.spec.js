import { shallowMount } from '@vue/test-utils';
import LoginComponent from '@/components/LoginComponent.vue';
import Vuex from 'vuex';
import axios from 'axios';

jest.mock('axios');

describe('LoginComponent', () => {
    let actions;
    let getters;
    let store;

    beforeEach(() => {
        actions = {
            login: jest.fn(),
        };
        getters = {
            isAuthenticated: () => false,
        };

        store = new Vuex.Store({
            modules: {
                login: {
                    namespaced: true,
                    actions,
                    getters,
                },
            },
        });
    });

    it('validates input fields correctly', async () => {
        const wrapper = shallowMount(LoginComponent, {
            global: {
                mocks: {
                    $store: store,
                },
                stubs: {
                    'router-link': true
                }
            },
        });

        // Case 1: Empty email and password
        wrapper.find('input[type="email"]').setValue('');
        wrapper.find('input[type="password"]').setValue('');
        expect(wrapper.vm.validateInput()).toBe(false);

        // Case 2: Valid email and password
        wrapper.find('input[type="email"]').setValue('test@example.com');
        wrapper.find('input[type="password"]').setValue('password');
        expect(wrapper.vm.validateInput()).toBe(true);
    });

});
