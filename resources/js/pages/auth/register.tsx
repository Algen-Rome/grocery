import { Head, useForm, Link } from '@inertiajs/react';
import { FormEventHandler, ChangeEvent } from 'react';

interface RegisterForm {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    [key: string]: string | boolean;
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
        <div className="fixed inset-0 z-[9999] w-full min-h-screen bg-[#1b5335] flex items-center justify-center p-4 font-sans text-gray-900 overflow-y-auto">
            <Head title="Register" />

            {/* Main Container Box */}
            <div className="bg-white w-full max-w-[480px] rounded-2xl shadow-xl overflow-hidden flex flex-col items-center px-10 py-12 my-auto min-h-[640px]">

                {/* App Logo Pill */}
                <div className="bg-[#1b5335] text-white font-black text-xs tracking-wider uppercase px-8 py-3 rounded-full mb-8 shadow-sm">
                    Grocery Store App
                </div>

                {/* Header Titles */}
                <div className="w-full text-left mb-5">
                    <h1 className="text-xl font-bold text-gray-900">Create Account</h1>
                    <p className="text-xs text-gray-400 mt-1">Connect with us today! Fill up your fields to get started.</p>
                </div>

                <form onSubmit={submit} className="w-full flex-1 flex flex-col justify-between">
                    <div className="space-y-3.5">
                        {/* Full Name Field Input */}
                        <div>
                            <label htmlFor="name" className="block text-xs font-bold text-gray-800 mb-1">
                                Full Name
                            </label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value={data.name}
                                className="w-full bg-gray-100 border-none rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b5335]"
                                autoComplete="name"
                                onChange={(e: ChangeEvent<HTMLInputElement>) => setData('name', e.target.value)}
                                required
                            />
                            {errors.name && (
                                <p className="mt-1 text-xs text-red-500 font-semibold">{errors.name}</p>
                            )}
                        </div>

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
                                className="w-full bg-gray-100 border-none rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b5335]"
                                autoComplete="username"
                                onChange={(e: ChangeEvent<HTMLInputElement>) => setData('email', e.target.value)}
                                required
                            />
                            {errors.email && (
                                <p className="mt-1 text-xs text-red-500 font-semibold">{errors.email}</p>
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
                                className="w-full bg-gray-100 border-none rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b5335]"
                                autoComplete="new-password"
                                onChange={(e: ChangeEvent<HTMLInputElement>) => setData('password', e.target.value)}
                                required
                            />
                            {errors.password && (
                                <p className="mt-1 text-xs text-red-500 font-semibold">{errors.password}</p>
                            )}
                        </div>

                        {/* Confirm Password Field Input */}
                        <div>
                            <label htmlFor="password_confirmation" className="block text-xs font-bold text-gray-800 mb-1">
                                Confirm Password
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                value={data.password_confirmation}
                                className="w-full bg-gray-100 border-none rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b5335]"
                                autoComplete="new-password"
                                onChange={(e: ChangeEvent<HTMLInputElement>) => setData('password_confirmation', e.target.value)}
                                required
                            />
                            {errors.password_confirmation && (
                                <p className="mt-1 text-xs text-red-500 font-semibold">{errors.password_confirmation}</p>
                            )}
                        </div>

                        {/* Terms Agreement Checkbox */}
                        <div className="text-[11px] font-semibold text-gray-400 pt-1">
                            <label className="flex items-center space-x-1.5 cursor-pointer">
                                <input
                                    type="checkbox"
                                    required
                                    className="accent-[#1b5335] rounded"
                                />
                                <span>I agree with the Terms and Privacy Policy</span>
                            </label>
                        </div>
                    </div>

                    {/* Lower Submit Action Hooks Wrapper Group */}
                    <div className="mt-6 space-y-4 text-center">
                        <button
                            type="submit"
                            className="w-full bg-[#1b5335] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#154129] flex justify-center items-center transition shadow-md disabled:opacity-50"
                            disabled={processing}
                        >
                            Create Account
                        </button>

                        <p className="text-xs text-gray-400 font-semibold">
                            Already have an account?{' '}
                            <Link href={route('login')} className="text-gray-900 font-bold hover:underline">
                                Log In
                            </Link>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    );
}
