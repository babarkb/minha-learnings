import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { useMemo } from 'react';

export default function WorksheetForm({
    data,
    setData,
    errors,
    processing,
    onSubmit,
    pageTypes = [],
    worksheet = null,
    submitLabel = 'Save',
    mode = 'create',
}) {
    const selectedPageType = useMemo(() => {
        const fromSelect = pageTypes.find(
            (type) => String(type.id) === String(data.page_type_id),
        );

        if (fromSelect) {
            return fromSelect;
        }

        return worksheet?.page_type ?? null;
    }, [data.page_type_id, pageTypes, worksheet]);

    const expectedImages = selectedPageType?.sections ?? 0;

    return (
        <form onSubmit={onSubmit} className="space-y-6">
            <div className="grid gap-6 lg:grid-cols-2">
                <div className="space-y-4">
                    <div>
                        <InputLabel htmlFor="title" value="Worksheet title" />
                        <TextInput
                            id="title"
                            value={data.title}
                            className="mt-1 block w-full"
                            onChange={(event) => setData('title', event.target.value)}
                            required
                        />
                        <InputError message={errors.title} className="mt-2" />
                    </div>

                    <div>
                        <InputLabel htmlFor="notes" value="Notes (optional)" />
                        <textarea
                            id="notes"
                            value={data.notes ?? ''}
                            className="mt-1 block w-full rounded-md border border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            rows={4}
                            onChange={(event) => setData('notes', event.target.value)}
                        />
                        <InputError message={errors.notes} className="mt-2" />
                    </div>

                    <div>
                        <InputLabel htmlFor="page_type_id" value="Layout" />
                        {mode === 'create' ? (
                            <select
                                id="page_type_id"
                                className="mt-1 block w-full rounded-md border border-slate-300 bg-white py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                value={data.page_type_id || ''}
                                onChange={(event) => setData('page_type_id', event.target.value)}
                                required
                            >
                                <option value="" disabled>
                                    Select a layout
                                </option>
                                {pageTypes.map((type) => (
                                    <option key={type.id} value={type.id}>
                                        {type.name} ({type.columns} × {type.rows})
                                    </option>
                                ))}
                            </select>
                        ) : (
                            <div className="mt-1 rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                {selectedPageType?.name} ({selectedPageType?.columns} ×{' '}
                                {selectedPageType?.rows})
                            </div>
                        )}
                        <InputError message={errors.page_type_id} className="mt-2" />
                    </div>
                </div>

                <div className="space-y-4">
                    <div>
                        <InputLabel htmlFor="images" value="Worksheet images" />
                        <input
                            id="images"
                            type="file"
                            accept="image/*"
                            multiple
                            className="mt-1 block w-full text-sm text-slate-600"
                            onChange={(event) =>
                                setData(
                                    'images',
                                    event.target.files
                                        ? Array.from(event.target.files)
                                        : [],
                                )
                            }
                        />
                        <InputError message={errors.images} className="mt-2" />
                        <p className="mt-2 text-sm text-slate-500">
                            {expectedImages > 0
                                ? `Upload exactly ${expectedImages} image${expectedImages === 1 ? '' : 's'} for this layout.`
                                : 'Select a layout to see how many images are required.'}
                        </p>
                        {mode === 'edit' && (
                            <p className="text-xs text-slate-400">
                                Leave this field blank to keep the existing images.
                            </p>
                        )}
                    </div>

                    {selectedPageType && (
                        <div className="rounded-md border border-dashed border-indigo-300 bg-indigo-50 p-4 text-sm text-indigo-700">
                            <p className="font-semibold">Layout overview</p>
                            <p className="mt-1">
                                {selectedPageType.columns} columns × {selectedPageType.rows} rows
                                ({selectedPageType.sections} sections total).
                            </p>
                            {selectedPageType.logo_url && (
                                <div className="mt-3">
                                    <p className="text-xs uppercase tracking-wide text-indigo-500">
                                        Logo preview
                                    </p>
                                    <img
                                        src={selectedPageType.logo_url}
                                        alt="Layout logo"
                                        className="mt-2 h-16 w-16 rounded border border-indigo-200 object-contain"
                                    />
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </div>

            {worksheet?.images?.length > 0 && (
                <div>
                    <p className="text-sm font-medium text-slate-700">
                        Current images
                    </p>
                    <div
                        className="mt-3 grid gap-3"
                        style={{
                            gridTemplateColumns: `repeat(${selectedPageType?.columns ?? 2}, minmax(0, 1fr))`,
                        }}
                    >
                        {worksheet.images.map((image) => (
                            <div
                                key={image.id}
                                className="overflow-hidden rounded-md border border-slate-200 bg-white"
                            >
                                <img
                                    src={image.image_url}
                                    alt={`Section ${image.section_index + 1}`}
                                    className="h-32 w-full object-cover"
                                />
                            </div>
                        ))}
                    </div>
                </div>
            )}

            <div className="flex justify-end">
                <PrimaryButton disabled={processing}>{submitLabel}</PrimaryButton>
            </div>
        </form>
    );
}
