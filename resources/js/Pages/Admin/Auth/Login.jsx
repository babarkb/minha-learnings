import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Login({ status }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (event) => {
        event.preventDefault();

        post(route('admin.login.store'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Admin Login" />

            <h1 className="mb-6 text-center text-2xl font-bold text-slate-800">
                Admin Access
            </h1>

            {status && (
                <div className="mb-4 rounded-md bg-emerald-50 p-3 text-sm font-medium text-emerald-700">
                    {status}
                </div>
            )}

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4 flex items-center justify-between">
                    <label className="flex items-center text-sm text-slate-600">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(event) =>
                                setData('remember', event.target.checked)
                            }
                        />
                        <span className="ms-2">Remember me</span>
                    </label>
                </div>

                <div className="mt-6">
                    <PrimaryButton className="w-full justify-center" disabled={processing}>
                        Sign in
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
