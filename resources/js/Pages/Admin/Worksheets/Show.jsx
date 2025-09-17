import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ worksheet }) {
    const columns = worksheet.page_type?.columns ?? 1;
    const rows = worksheet.page_type?.rows ?? 1;

    const handlePrint = () => {
        window.print();
    };

    return (
        <AdminLayout
            header={
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 className="text-2xl font-semibold leading-tight text-slate-800">
                            {worksheet.title}
                        </h2>
                        <p className="text-sm text-slate-500">
                            {worksheet.page_type?.name} layout — {worksheet.page_type?.sections} sections
                        </p>
                    </div>
                    <div className="flex items-center gap-3">
                        <Link
                            href={route('admin.worksheets.edit', worksheet.id)}
                            className="inline-flex items-center rounded-md border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100"
                        >
                            Edit worksheet
                        </Link>
                        <button
                            type="button"
                            onClick={handlePrint}
                            className="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-indigo-700 print:hidden"
                        >
                            Print
                        </button>
                    </div>
                </div>
            }
        >
            <Head title={`${worksheet.title} Preview`} />

            <div className="flex justify-center">
                <div className="a4-sheet">
                    <header className="mb-6 flex items-start justify-between gap-4">
                        {worksheet.page_type?.logo_url ? (
                            <img
                                src={worksheet.page_type.logo_url}
                                alt="Worksheet logo"
                                className="h-16 w-16 object-contain"
                            />
                        ) : (
                            <div className="h-16 w-16" aria-hidden="true" />
                        )}
                        <div className="flex-1 text-center">
                            <h1 className="text-3xl font-semibold text-slate-800">
                                {worksheet.title}
                            </h1>
                            {worksheet.notes && (
                                <p className="mt-2 text-sm text-slate-500">{worksheet.notes}</p>
                            )}
                        </div>
                    </header>

                    <div
                        className="a4-grid"
                        style={{
                            gridTemplateColumns: `repeat(${columns}, minmax(0, 1fr))`,
                            gridTemplateRows: `repeat(${rows}, minmax(0, 1fr))`,
                        }}
                    >
                        {worksheet.images.map((image) => (
                            <div key={image.id} className="a4-grid__cell">
                                <img
                                    src={image.image_url}
                                    alt={`Worksheet item ${image.section_index + 1}`}
                                    className="h-full w-full object-contain"
                                />
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
