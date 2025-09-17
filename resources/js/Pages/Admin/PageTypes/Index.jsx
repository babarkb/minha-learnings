import DangerButton from '@/Components/DangerButton';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link, router } from '@inertiajs/react';

export default function Index({ pageTypes }) {
    const handleDelete = (id) => {
        if (
            !window.confirm(
                'Deleting this page type will remove all related worksheets. Continue?',
            )
        ) {
            return;
        }

        router.delete(route('admin.page-types.destroy', id));
    };

    return (
        <AdminLayout
            header={
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 className="text-2xl font-semibold leading-tight text-slate-800">
                            Page Types
                        </h2>
                        <p className="text-sm text-slate-500">
                            Define printable layouts that can be reused across worksheets.
                        </p>
                    </div>
                    <Link
                        href={route('admin.page-types.create')}
                        className="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-indigo-700"
                    >
                        Create page type
                    </Link>
                </div>
            }
        >
            <Head title="Page Types" />

            <div className="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <table className="min-w-full divide-y divide-slate-200">
                    <thead className="bg-slate-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                                Name
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                                Layout
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                                Sections
                            </th>
                            <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-slate-500">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-200">
                        {pageTypes.data.length === 0 && (
                            <tr>
                                <td
                                    colSpan={4}
                                    className="px-6 py-8 text-center text-sm text-slate-500"
                                >
                                    No page types created yet.
                                </td>
                            </tr>
                        )}

                        {pageTypes.data.map((pageType) => (
                            <tr key={pageType.id}>
                                <td className="px-6 py-4 text-sm font-medium text-slate-800">
                                    <div className="flex items-center gap-3">
                                        {pageType.logo_url && (
                                            <img
                                                src={pageType.logo_url}
                                                alt="Logo"
                                                className="h-10 w-10 rounded border border-slate-200 object-contain"
                                            />
                                        )}
                                        <span>{pageType.name}</span>
                                    </div>
                                </td>
                                <td className="px-6 py-4 text-sm text-slate-600">
                                    {pageType.columns} × {pageType.rows}
                                </td>
                                <td className="px-6 py-4 text-sm text-slate-600">
                                    {pageType.sections}
                                </td>
                                <td className="px-6 py-4">
                                    <div className="flex justify-end gap-2">
                                        <Link
                                            href={route('admin.page-types.edit', pageType.id)}
                                            className="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                        >
                                            Edit
                                        </Link>
                                        <DangerButton
                                            onClick={() => handleDelete(pageType.id)}
                                            className="text-sm"
                                        >
                                            Delete
                                        </DangerButton>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {(pageTypes.links.prev || pageTypes.links.next) && (
                <div className="mt-4 flex items-center justify-between">
                    <button
                        type="button"
                        disabled={!pageTypes.links.prev}
                        onClick={() =>
                            pageTypes.links.prev && router.visit(pageTypes.links.prev)
                        }
                        className="rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <span className="text-sm text-slate-500">
                        Page {pageTypes.meta.current_page} of {pageTypes.meta.last_page}
                    </span>
                    <button
                        type="button"
                        disabled={!pageTypes.links.next}
                        onClick={() =>
                            pageTypes.links.next && router.visit(pageTypes.links.next)
                        }
                        className="rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            )}
        </AdminLayout>
    );
}
