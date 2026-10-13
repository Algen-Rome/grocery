import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';

interface LoginForm {
    email: string;
    password: string;
    remember: boolean;
    [key: string]: string | boolean;
}

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const { data, setData, post, processing, errors, reset } = useForm<LoginForm>({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <div className="min-h-screen w-full bg-emerald-800 flex items-center justify-center sm:p-0 font-sans">
            <Head title="Log in" />

            <div className="bg-white min-w-150 max-w-md min-h-screen shadow-2xl p-8 sm:p-12 rounded-[2rem] flex flex-col text-gray-900 rounded-none justify-center">

                <div className="bg-emerald-800 text-white px-6 py-10 rounded-full text-center font-black tracking-widest text-2xl mb-8 mx-auto w-fit uppercase shadow-sm font-extrabold">
                    GROCERY STORE APP
                </div>
                <div className="text-center mb-6">
                    <h2 className="text-3xl font-black text-gray-900 tracking-tight">Welcome</h2>
                    <p className="text-sm text-gray-400 font-semibold mt-1">Please login to your admin account</p>
                </div>

                <form className="flex flex-col gap-5" onSubmit={submit}>
                    <div className="grid gap-5 px-20">
                        <div className="grid gap-2">
                            <Label htmlFor="email" className="text-gray-700 font-extrabold text-lg tracking-wider text-left block">Email address</Label>
                            <Input
                                id="email"
                                type="email"
                                required
                                autoFocus
                                tabIndex={1}
                                autoComplete="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                placeholder="email@example.com"
                                className="w-full border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition font-medium text-sm h-auto bg-gray-50 text-gray-900 placeholder-gray-400"
                            />
                            <InputError message={errors.email} />
                        </div>

                        <div className="grid gap-2">
                            <div className="flex items-center justify-between">
                                <Label htmlFor="password" className="text-gray-700 font-extrabold text-lg tracking-wider">Password</Label>
                            </div>
                            <Input
                                id="password"
                                type="password"
                                required
                                tabIndex={2}
                                autoComplete="current-password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                placeholder="Password"
                                className="w-full border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 transition font-medium text-sm h-auto bg-gray-50 text-gray-900 placeholder-gray-400"
                            />
                            <InputError message={errors.password} />
                        </div>

                        <div className="flex items-center space-x-3 space-y-0">
                            <Checkbox id="remember" name="remember" tabIndex={3} className="border-gray-300 data-[state=checked]:bg-emerald-800 data-[state=checked]:border-emerald-800 rounded-full" />
                            <div className="flex-1 flex items-center justify-between">
                                <Label htmlFor="remember" className="text-sm font-medium text-gray-600 cursor-pointer select-none">Remember me</Label>
                                {canResetPassword && (
                                    <TextLink href={route('password.request')} className="text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition" tabIndex={5}>
                                        Forgot password?
                                    </TextLink>
                                )}
                            </div>
                        </div>

                        <Button type="submit" className="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-black py-6  shadow-lg transition-all tracking-wide block text-center flex flex-col justify-center mt-2 border-none text-sm uppercase " tabIndex={4} disabled={processing}>
                            {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2 inline" />}
                            Log in
                        </Button>
                    </div>

                    <div className="text-gray-400 text-center text-sm mt-4 font-medium">
                        Don't have an account?{' '}
                        <TextLink href={route('register')} className="text-emerald-700 font-bold hover:underline ml-1 transition" tabIndex={5}>
                            Sign up
                        </TextLink>
                    </div>
                </form>
            </div>

            {status && <div className="mt-4 text-center text-sm font-semibold text-white bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm">{status}</div>}
        </div>
    );
}