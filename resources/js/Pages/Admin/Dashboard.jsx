import AdminLayout from '@/Layouts/AdminLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ metrics }) {
    return (
        <AdminLayout
            header={
                <div>
                    <h2 className="text-3xl font-semibold leading-tight text-slate-800">
                        Minha Learnings
                    </h2>
                    <p className="mt-1 text-sm text-slate-500">
                        Welcome back to your activity workspace.
                    </p>
                </div>
            }
        >
            <Head title="Admin Dashboard" />

            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <MetricCard label="Page Types" value={metrics?.page_types ?? 0} />
                <MetricCard label="Worksheets" value={metrics?.worksheets ?? 0} />
            </div>
        </AdminLayout>
    );
}

function MetricCard({ label, value }) {
    return (
        <div className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <p className="text-sm uppercase tracking-wide text-slate-500">
                {label}
            </p>
            <p className="mt-2 text-3xl font-semibold text-slate-800">{value}</p>
        </div>
    );
}
