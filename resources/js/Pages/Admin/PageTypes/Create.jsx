import PageTypeForm from '@/Components/Admin/PageTypeForm';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Create() {
    const form = useForm({
        name: '',
        columns: 2,
        rows: 4,
        logo: null,
    });

    const submit = (event) => {
        event.preventDefault();
        form.post(route('admin.page-types.store'), {
            forceFormData: true,
            onSuccess: () => form.setData('logo', null),
        });
    };

    return (
        <AdminLayout
            header={
                <h2 className="text-2xl font-semibold leading-tight text-slate-800">
                    Create page type
                </h2>
            }
        >
            <Head title="Create Page Type" />

            <PageTypeForm
                data={form.data}
                setData={form.setData}
                errors={form.errors}
                processing={form.processing}
                onSubmit={submit}
                pageType={null}
                submitLabel="Create"
            />
        </AdminLayout>
    );
}
