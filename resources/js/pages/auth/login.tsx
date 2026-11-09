import { Head, useForm, Link } from '@inertiajs/react';
import { FormEventHandler, ChangeEvent } from 'react';

interface LoginForm {
    email: string;
    password: string;
    remember: boolean;
    [key: string]: string | boolean;
}

interface LoginProps {
    status?: string;
    canResetPassword?: boolean;
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
        <div className="min-h-screen w-full bg-[#1b5335] flex items-center justify-center p-4 font-sans text-gray-900">
            <Head title="Log in" />

            {/* Main Form Box Container */}
            <div className="bg-white w-full max-w-[480px] rounded-2xl shadow-xl overflow-hidden flex flex-col items-center px-10 py-12 min-h-[640px]">

                {/* App Logo Pill */}
                <div className="bg-[#1b5335] text-white font-black text-xs tracking-wider uppercase px-8 py-3 rounded-full mb-12 shadow-sm">
                    Grocery Store App
                </div>

                {/* Welcome Header Titles */}
                <div className="w-full text-left mb-6">
                    <h1 className="text-xl font-bold text-gray-900">Welcome</h1>
                    <p className="text-xs text-gray-400 mt-1">Please log in to your account</p>
                </div>

                {status && (
                    <div className="w-full mb-4 text-sm font-medium text-emerald-600">
                        {status}
                    </div>
                )}

                <form onSubmit={submit} className="w-full flex-1 flex flex-col justify-between">
                    <div className="space-y-4">
                        {/* Email Address Field Input */}
                        <div>
                            <label htmlFor="email" className="block text-xs font-bold text-gray-800 mb-1">
                                Email Address
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                className="w-full bg-gray-100 border-none rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b5335]"
                                autoComplete="username"
                                onChange={(e: ChangeEvent<HTMLInputElement>) => setData('email', e.target.value)}
                                required
                            />
                            {errors.email && (
                                <p className="mt-2 text-xs text-red-500 font-semibold">{errors.email}</p>
                            )}
                        </div>

                        {/* Password Field Input */}
                        <div>
                            <label htmlFor="password" className="block text-xs font-bold text-gray-800 mb-1">
                                Password
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                className="w-full bg-gray-100 border-none rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b5335]"
                                autoComplete="current-password"
                                onChange={(e: ChangeEvent<HTMLInputElement>) => setData('password', e.target.value)}
                                required
                            />
                            {errors.password && (
                                <p className="mt-2 text-xs text-red-500 font-semibold">{errors.password}</p>
                            )}
                        </div>

                        {/* Remember Me and Forgot Password Row */}
                        <div className="flex items-center justify-between text-[11px] font-semibold text-gray-400 pt-1">
                            <label className="flex items-center space-x-1.5 cursor-pointer">
                                <input
                                    id="remember"
                                    type="checkbox"
                                    name="remember"
                                    checked={data.remember}
                                    onChange={(e: ChangeEvent<HTMLInputElement>) => setData('remember', e.target.checked)}
                                    className="accent-[#1b5335] rounded"
                                />
                                <span className="text-gray-400">Remember me</span>
                            </label>

                            {canResetPassword && (
                                <Link
                                    href={route('password.request')}
                                    className="text-gray-900 font-bold hover:underline"
                                >
                                    Forgot Password?
                                </Link>
                            )}
                        </div>
                    </div>

                    {/* Lower Submit Action Hooks Wrapper Group */}
                    <div className="mt-8 space-y-4 text-center">
                        <button
                            type="submit"
                            className="w-full bg-[#1b5335] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#154129] flex justify-center items-center transition shadow-md disabled:opacity-50"
                            disabled={processing}
                        >
                            Log in
                        </button>

                        <p className="text-xs text-gray-400 font-semibold">
                            Don't have an account?{' '}
                            <Link href={route('register')} className="text-gray-900 font-bold hover:underline">
                                Sign Up
                            </Link>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    );
}
