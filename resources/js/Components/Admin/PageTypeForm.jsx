import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';

export default function PageTypeForm({
    data,
    setData,
    errors,
    processing,
    onSubmit,
    pageType,
    submitLabel = 'Save',
}) {
    return (
        <form onSubmit={onSubmit} className="space-y-6">
            <div>
                <InputLabel htmlFor="name" value="Page Type Name" />
                <TextInput
                    id="name"
                    value={data.name}
                    className="mt-1 block w-full"
                    onChange={(event) => setData('name', event.target.value)}
                    required
                />
                <InputError message={errors.name} className="mt-2" />
            </div>

            <div className="grid gap-6 sm:grid-cols-2">
                <div>
                    <InputLabel htmlFor="columns" value="Columns" />
                    <TextInput
                        id="columns"
                        type="number"
                        min="1"
                        max="10"
                        value={data.columns}
                        className="mt-1 block w-full"
                        onChange={(event) => setData('columns', event.target.value)}
                        required
                    />
                    <InputError message={errors.columns} className="mt-2" />
                </div>

                <div>
                    <InputLabel htmlFor="rows" value="Rows" />
                    <TextInput
                        id="rows"
                        type="number"
                        min="1"
                        max="10"
                        value={data.rows}
                        className="mt-1 block w-full"
                        onChange={(event) => setData('rows', event.target.value)}
                        required
                    />
                    <InputError message={errors.rows} className="mt-2" />
                </div>
            </div>

            <div>
                <InputLabel htmlFor="logo" value="Logo" />
                <input
                    id="logo"
                    type="file"
                    accept="image/*"
                    className="mt-1 block w-full text-sm text-slate-600"
                    onChange={(event) => setData('logo', event.target.files[0])}
                />
                <InputError message={errors.logo} className="mt-2" />
                {pageType?.logo_url && (
                    <div className="mt-3 flex items-center gap-3">
                        <img
                            src={pageType.logo_url}
                            alt={`${pageType.name} logo`}
                            className="h-16 w-16 rounded border border-slate-200 object-contain"
                        />
                        <p className="text-sm text-slate-500">
                            Uploading a new file will replace the existing logo.
                        </p>
                    </div>
                )}
            </div>

            <div className="rounded-md bg-slate-50 p-4 text-sm text-slate-600">
                <p>
                    Columns × rows determine how many activity slots this layout
                    provides. The total number of sections must be 12 or fewer
                    so the worksheet remains printable on A4 paper.
                </p>
                <p className="mt-2 font-medium text-slate-700">
                    Current configuration:{' '}
                    <span className="font-semibold text-indigo-600">
                        {Number(data.columns || 0) * Number(data.rows || 0)}
                    </span>{' '}
                    sections.
                </p>
            </div>

            <div className="flex justify-end">
                <PrimaryButton disabled={processing}>{submitLabel}</PrimaryButton>
            </div>
        </form>
    );
}
