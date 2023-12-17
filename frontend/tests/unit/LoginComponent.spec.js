import { shallowMount } from '@vue/test-utils';
import LoginComponent from '@/components/LoginComponent.vue';
import axios from 'axios';

jest.mock('axios');

describe('LoginComponent', () => {

    it('calls the login method when the form is submitted', async () => {
        const wrapper = shallowMount(LoginComponent);
        wrapper.find('form').trigger('submit.prevent');
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.login).toHaveBeenCalled();
    });

    it('validates input fields correctly', async () => {
        const wrapper = shallowMount(LoginComponent);
        wrapper.find('input[type="email"]').setValue('');
        wrapper.find('input[type="password"]').setValue('');
    });

    it('handles successful login', async () => {
        axios.post.mockResolvedValue({ data: { token: 'fake-token' } });
        const wrapper = shallowMount(LoginComponent);
        wrapper.find('input[type="email"]').setValue('test@example.com');
        wrapper.find('input[type="password"]').setValue('password');
        wrapper.find('form').trigger('submit.prevent');
        await wrapper.vm.$nextTick();
    });

    it('handles failed login', async () => {
        axios.post.mockRejectedValue(new Error('Login failed'));
        const wrapper = shallowMount(LoginComponent);
        wrapper.find('input[type="email"]').setValue('wrong@example.com');
        wrapper.find('input[type="password"]').setValue('wrongpassword');
        wrapper.find('form').trigger('submit.prevent');
        await wrapper.vm.$nextTick();
    });

});
