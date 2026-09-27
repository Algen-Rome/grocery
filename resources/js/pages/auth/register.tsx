import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';

interface RegisterForm {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    [key: string]: any;
}

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm<RegisterForm>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <div className="min-h-screen w-full bg-emerald-800 flex items-center justify-center sm:p-0 font-sans">
            <Head title="Register" />

            <div className="bg-white min-w-150 max-w-md min-h-screen shadow-2xl p-8 sm:p-12 rounded-[2rem] flex flex-col justify-center text-gray-900 rounded-none justify-center">

                <div className="bg-emerald-800 text-white px-6 py-10 rounded-full text-center font-black tracking-widest text-2xl mb-8 mx-auto w-fit uppercase shadow-sm font-extrabold">
                    GROCERY STORE APP
                </div>

                <div className="text-center mb-6">
                    <h2 className="text-3xl font-black text-gray-900 tracking-tight">Create Account</h2>
                    <p className="text-sm text-gray-400 font-semibold mt-1">Register a new admin profile user</p>
                </div>

                <form className="flex flex-col gap-5" onSubmit={submit}>
                    <div className="grid gap-5 px-20">
                        
                        <div className="grid gap-2">
                            <Label htmlFor="name" className="text-gray-700 font-extrabold text-lg tracking-wider text-left block">Name</Label>
                            <Input
                                id="name"
                                type="text"
                                required
                                autoFocus
                                tabIndex={1}
                                autoComplete="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                disabled={processing}
                                placeholder="Full name"
                                className="w-full border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition font-medium text-sm h-auto bg-gray-50 text-gray-900 placeholder-gray-400"
                            />
                            <InputError message={errors.name} className="mt-2 text-left" />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="email" className="text-gray-700 font-extrabold text-lg tracking-wider text-left block">Email address</Label>
                            <Input
                                id="email"
                                type="email"
                                required
                                tabIndex={2}
                                autoComplete="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                disabled={processing}
                                placeholder="email@example.com"
                                className="w-full border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition font-medium text-sm h-auto bg-gray-50 text-gray-900 placeholder-gray-400"
                            />
                            <InputError message={errors.email} className="text-left" />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password" className="text-gray-700 font-extrabold text-lg tracking-wider text-left block w-full">Password</Label>
                            <Input
                                id="password"
                                type="password"
                                required
                                tabIndex={3}
                                autoComplete="new-password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                disabled={processing}
                                placeholder="Password"
                                className="w-full border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition font-medium text-sm h-auto bg-gray-50 text-gray-900 placeholder-gray-400"
                            />
                            <InputError message={errors.password} className="text-left" />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password_confirmation" className="text-gray-700 font-extrabold text-lg tracking-wider text-left block w-full">Confirm password</Label>
                            <Input
                                id="password_confirmation"
                                type="password"
                                required
                                tabIndex={4}
                                autoComplete="new-password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                disabled={processing}
                                placeholder="Confirm password"
                                className="w-full border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition font-medium text-sm h-auto bg-gray-50 text-gray-900 placeholder-gray-400"
                            />
                            <InputError message={errors.password_confirmation} className="text-left" />
                        </div>

                        <Button type="submit" className="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-black py-4 rounded-sm shadow-lg transition-all tracking-wide block text-center mt-2 border-none text-sm uppercase" tabIndex={5} disabled={processing}>
                            {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2 inline" />}
                            Create account
                        </Button>
                    </div>

                    <div className="text-gray-400 text-center text-sm mt-4 font-medium">
                        Already have an account?{' '}
                        <TextLink href={route('login')} className="text-emerald-700 font-bold hover:underline ml-1 transition" tabIndex={6}>
                            Log in
                        </TextLink>
                </div>
            </form>
        </div>
    </div>
);
}
