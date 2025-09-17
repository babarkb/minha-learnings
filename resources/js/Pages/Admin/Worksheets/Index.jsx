import DangerButton from '@/Components/DangerButton';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link, router } from '@inertiajs/react';

export default function Index({ worksheets }) {
    const handleDelete = (id) => {
        if (!window.confirm('Are you sure you want to delete this worksheet?')) {
            return;
        }

        router.delete(route('admin.worksheets.destroy', id));
    };

    return (
        <AdminLayout
            header={
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 className="text-2xl font-semibold leading-tight text-slate-800">
                            Worksheets
                        </h2>
                        <p className="text-sm text-slate-500">
                            Upload activity sheets that match your selected page type layouts.
                        </p>
                    </div>
                    <Link
                        href={route('admin.worksheets.create')}
                        className="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-indigo-700"
                    >
                        Create worksheet
                    </Link>
                </div>
            }
        >
            <Head title="Worksheets" />

            <div className="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <table className="min-w-full divide-y divide-slate-200">
                    <thead className="bg-slate-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                                Title
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                                Layout
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                                Uploaded
                            </th>
                            <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-slate-500">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-200">
                        {worksheets.data.length === 0 && (
                            <tr>
                                <td
                                    colSpan={4}
                                    className="px-6 py-8 text-center text-sm text-slate-500"
                                >
                                    No worksheets have been created yet.
                                </td>
                            </tr>
                        )}

                        {worksheets.data.map((worksheet) => (
                            <tr key={worksheet.id}>
                                <td className="px-6 py-4 text-sm font-medium text-slate-800">
                                    {worksheet.title}
                                </td>
                                <td className="px-6 py-4 text-sm text-slate-600">
                                    {worksheet.page_type?.name} ({worksheet.page_type?.sections} sections)
                                </td>
                                <td className="px-6 py-4 text-sm text-slate-600">
                                    {new Date(worksheet.created_at).toLocaleDateString()}
                                </td>
                                <td className="px-6 py-4">
                                    <div className="flex justify-end gap-3 text-sm font-medium">
                                        <Link
                                            href={route('admin.worksheets.show', worksheet.id)}
                                            className="text-indigo-600 hover:text-indigo-800"
                                        >
                                            Preview
                                        </Link>
                                        <Link
                                            href={route('admin.worksheets.edit', worksheet.id)}
                                            className="text-slate-600 hover:text-slate-800"
                                        >
                                            Edit
                                        </Link>
                                        <DangerButton
                                            onClick={() => handleDelete(worksheet.id)}
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

            {(worksheets.links.prev || worksheets.links.next) && (
                <div className="mt-4 flex items-center justify-between">
                    <button
                        type="button"
                        disabled={!worksheets.links.prev}
                        onClick={() =>
                            worksheets.links.prev && router.visit(worksheets.links.prev)
                        }
                        className="rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <span className="text-sm text-slate-500">
                        Page {worksheets.meta.current_page} of {worksheets.meta.last_page}
                    </span>
                    <button
                        type="button"
                        disabled={!worksheets.links.next}
                        onClick={() =>
                            worksheets.links.next && router.visit(worksheets.links.next)
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
