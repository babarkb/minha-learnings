import WorksheetForm from '@/Components/Admin/WorksheetForm';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Create({ pageTypes }) {
    const form = useForm({
        title: '',
        notes: '',
        page_type_id: '',
        images: [],
    });

    const submit = (event) => {
        event.preventDefault();
        form.post(route('admin.worksheets.store'), {
            forceFormData: true,
            onSuccess: () => {
                form.reset('title', 'notes', 'page_type_id');
                form.setData('images', []);
            },
        });
    };

    return (
        <AdminLayout
            header={
                <h2 className="text-2xl font-semibold leading-tight text-slate-800">
                    Create worksheet
                </h2>
            }
        >
            <Head title="Create Worksheet" />

            <WorksheetForm
                data={form.data}
                setData={form.setData}
                errors={form.errors}
                processing={form.processing}
                onSubmit={submit}
                pageTypes={pageTypes}
                submitLabel="Create"
                mode="create"
            />
        </AdminLayout>
    );
}
