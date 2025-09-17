import WorksheetForm from '@/Components/Admin/WorksheetForm';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Edit({ worksheet }) {
    const form = useForm({
        title: worksheet.title || '',
        notes: worksheet.notes || '',
        page_type_id: worksheet.page_type_id,
        images: [],
    });

    const submit = (event) => {
        event.preventDefault();
        form.put(route('admin.worksheets.update', worksheet.id), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => form.setData('images', []),
        });
    };

    return (
        <AdminLayout
            header={
                <h2 className="text-2xl font-semibold leading-tight text-slate-800">
                    Edit {worksheet.title}
                </h2>
            }
        >
            <Head title={`Edit ${worksheet.title}`} />

            <WorksheetForm
                data={form.data}
                setData={form.setData}
                errors={form.errors}
                processing={form.processing}
                onSubmit={submit}
                worksheet={worksheet}
                submitLabel="Update"
                mode="edit"
            />
        </AdminLayout>
    );
}
