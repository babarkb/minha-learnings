import ApplicationLogo from '@/Components/ApplicationLogo';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function AdminLayout({ header, children }) {
    const { props } = usePage();
    const admin = props.auth?.admin;
    const status = props.status ?? props.flash?.status ?? null;
    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <div className="min-h-screen bg-slate-100">
            <nav className="border-b border-slate-200 bg-white">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 justify-between">
                        <div className="flex items-center gap-6">
                            <Link href={route('admin.dashboard')} className="flex items-center gap-2">
                                <ApplicationLogo className="block h-9 w-auto fill-current text-indigo-600" />
                                <span className="font-semibold text-slate-800">
                                    Minha Learnings Admin
                                </span>
                            </Link>

                            <div className="hidden space-x-8 sm:flex">
                                <NavLink
                                    href={route('admin.dashboard')}
                                    active={route().current('admin.dashboard')}
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    href={route('admin.page-types.index')}
                                    active={route().current('admin.page-types.*')}
                                >
                                    Page Types
                                </NavLink>
                                <NavLink
                                    href={route('admin.worksheets.index')}
                                    active={route().current('admin.worksheets.*')}
                                >
                                    Worksheets
                                </NavLink>
                            </div>
                        </div>

                        <div className="hidden sm:flex sm:items-center">
                            {admin && (
                                <div className="flex items-center gap-4">
                                    <div className="text-right">
                                        <p className="text-sm font-medium text-slate-700">
                                            {admin.name}
                                        </p>
                                        <p className="text-xs text-slate-500">{admin.email}</p>
                                    </div>
                                    <Link
                                        href={route('admin.logout')}
                                        method="post"
                                        as="button"
                                        className="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow transition hover:bg-indigo-700"
                                    >
                                        Log out
                                    </Link>
                                </div>
                            )}
                        </div>

                        <div className="flex items-center sm:hidden">
                            <button
                                onClick={() =>
                                    setShowingNavigationDropdown(
                                        (previous) => !previous,
                                    )
                                }
                                className="inline-flex items-center justify-center rounded-md p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 focus:bg-slate-100 focus:text-slate-700 focus:outline-none"
                            >
                                <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d={
                                            showingNavigationDropdown
                                                ? 'M6 18L18 6M6 6l12 12'
                                                : 'M4 6h16M4 12h16M4 18h16'
                                        }
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    className={
                        (showingNavigationDropdown ? 'block' : 'hidden') +
                        ' sm:hidden'
                    }
                >
                    <div className="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            href={route('admin.dashboard')}
                            active={route().current('admin.dashboard')}
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            href={route('admin.page-types.index')}
                            active={route().current('admin.page-types.*')}
                        >
                            Page Types
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            href={route('admin.worksheets.index')}
                            active={route().current('admin.worksheets.*')}
                        >
                            Worksheets
                        </ResponsiveNavLink>
                    </div>

                    <div className="border-t border-slate-200 pb-1 pt-4">
                        {admin && (
                            <div className="px-4">
                                <p className="text-base font-medium text-slate-800">
                                    {admin.name}
                                </p>
                                <p className="text-sm text-slate-500">{admin.email}</p>

                                <ResponsiveNavLink
                                    method="post"
                                    href={route('admin.logout')}
                                    as="button"
                                    className="mt-3"
                                >
                                    Log out
                                </ResponsiveNavLink>
                            </div>
                        )}
                    </div>
                </div>
            </nav>

            {status && (
                <div className="bg-emerald-50 py-3 text-center text-sm font-medium text-emerald-700">
                    {status}
                </div>
            )}

            {header && (
                <header className="bg-white shadow">
                    <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {header}
                    </div>
                </header>
            )}

            <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                {children}
            </main>
        </div>
    );
}
