import PageTypeForm from '@/Components/Admin/PageTypeForm';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Edit({ pageType }) {
    const form = useForm({
        name: pageType.name || '',
        columns: pageType.columns || 1,
        rows: pageType.rows || 1,
        logo: null,
    });

    const submit = (event) => {
        event.preventDefault();
        form.put(route('admin.page-types.update', pageType.id), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => form.setData('logo', null),
        });
    };

    return (
        <AdminLayout
            header={
                <h2 className="text-2xl font-semibold leading-tight text-slate-800">
                    Edit {pageType.name}
                </h2>
            }
        >
            <Head title={`Edit ${pageType.name}`} />

            <PageTypeForm
                data={form.data}
                setData={form.setData}
                errors={form.errors}
                processing={form.processing}
                onSubmit={submit}
                pageType={pageType}
                submitLabel="Update"
            />
        </AdminLayout>
    );
}
