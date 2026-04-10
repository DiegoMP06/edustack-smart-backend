import { useEffect, useState } from "react";
import { useForm } from "react-hook-form";
import type { SubmitHandler } from "react-hook-form";
import { toast } from "sonner";
import { Button } from "@/components/ui/shadcn/button";
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from "@/components/ui/shadcn/dialog";
import { Label } from "@/components/ui/shadcn/label";
import { Switch } from "@/components/ui/shadcn/switch";
import { generateUniqueId } from "@/lib/utils/uuid";
import type { Speaker, SpeakerFormData, UserFromAPI } from "@/types";
import SearchSpeaker from "./SearchSpeaker";
import SpeakerForm from "./SpeakerForm";

type ActivitySpeakerModalProps = {
    isModalOpen: boolean;
    setIsModalOpen: (open: boolean) => void;
    handleAddSpeaker: (speaker: Speaker) => void
    editingSpeaker: Speaker | null | undefined
}

export default function ActivitySpeakerModal({ isModalOpen, setIsModalOpen, handleAddSpeaker, editingSpeaker }: ActivitySpeakerModalProps) {
    const [isSearch, setIsSearch] = useState(false);

    const initialValues: SpeakerFormData = {
        name: '',
        father_last_name: '',
        mother_last_name: '',
        email: '',
        job_title: '',
        company: '',
        biography: '',
    }

    const { register, control, handleSubmit, setValue, reset } = useForm({
        defaultValues: initialValues,
    })

    const setExistingSpeaker = (speaker: UserFromAPI) => {
        setValue('name', speaker.name);
        setValue('father_last_name', speaker.father_last_name);
        setValue('mother_last_name', speaker.mother_last_name);
        setValue('email', speaker.email);

        if (speaker.bio) {
            setValue('biography', speaker.bio)
        }

        setIsSearch(false);
    }

    const handleSubmitAddSpeaker: SubmitHandler<SpeakerFormData> = (data) => {
        handleAddSpeaker({
            ...data,
            id: editingSpeaker?.id || generateUniqueId()
        });

        toast.success(
            editingSpeaker ?
                'Ponente actualizado correctamente' :
                'Ponente agregado correctamente'
        );
    }

    useEffect(() => {
        if (editingSpeaker) {
            setValue('name', editingSpeaker.name);
            setValue('father_last_name', editingSpeaker.father_last_name);
            setValue('mother_last_name', editingSpeaker.mother_last_name);
            setValue('email', editingSpeaker.email);
            setValue('job_title', editingSpeaker.job_title);
            setValue('company', editingSpeaker.company);
            setValue('biography', editingSpeaker.biography);
        } else {
            reset();
        }
    }, [editingSpeaker]);

    return (
        <Dialog open={isModalOpen} onOpenChange={setIsModalOpen}>
            <DialogContent className="sm:max-w-xl">
                <DialogHeader>
                    <DialogTitle>Agregar Ponente</DialogTitle>
                    <DialogDescription>
                        Aquí puedes agregar un nuevo ponente a la actividad
                    </DialogDescription>
                </DialogHeader>

                {!editingSpeaker && (
                    <div className="flex gap-2 items-center mb-4 mt-2">
                        <Switch
                            id="is_search"
                            checked={isSearch}
                            onCheckedChange={setIsSearch}
                        />

                        <Label
                            htmlFor="is_search"
                            className="font-bold"
                        >
                            Buscar Ponente
                        </Label>
                    </div>
                )}

                {isSearch ?
                    (<SearchSpeaker setExistingSpeaker={setExistingSpeaker} />) :
                    (
                        <form className="mx-auto grid w-full max-w-2xl grid-cols-1 gap-6" >
                            <SpeakerForm {...{
                                register,
                                control,
                            }} />

                            <Button type="button" onClick={handleSubmit(handleSubmitAddSpeaker)}>
                                {editingSpeaker ? 'Guardar Cambios' : 'Agregar Ponente'}
                            </Button>
                        </form>
                    )}

                <DialogFooter>
                    <DialogClose asChild>
                        <Button variant="outline">
                            Cancelar
                        </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}
