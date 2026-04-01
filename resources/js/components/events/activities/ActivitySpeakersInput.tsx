import { Plus } from "lucide-react";
import { useEffect, useState } from "react";
import { Button } from "@/components/ui/shadcn/button";
import type { Speaker } from "@/types";
import ActivitySpeakerModal from "./ActivitySpeakerModal";

type ActivitySpeakerInputProps = {
    onChange: (speakers: Speaker[]) => void;
    value: Speaker[];
}

export default function ActivitySpeakersInput({ onChange, value }: ActivitySpeakerInputProps) {
    const [speakers, setSpeakers] = useState<Speaker[]>(value);
    const [isModalOpen, setIsModalOpen] = useState(false);

    useEffect(() => {
        onChange(speakers);
    }, [speakers]);

    return (
        <>
            <div className="">
                <Button type="button" onClick={() => setIsModalOpen(true)}>
                    <Plus />
                    Agregar
                </Button>

                {speakers.length === 0 ? (
                    <p className="text-sm text-muted-foreground text-center my-10">
                        No hay ponentes asignados a esta actividad.
                    </p>
                ) : (
                    <div className="flex flex-wrap gap-2">
                        {speakers.map((speaker, index) => (
                            <div key={index} className="bg-primary text-primary-foreground px-3 py-1 rounded-full text-sm">
                                {speaker.name}
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {isModalOpen && (
                <ActivitySpeakerModal
                    isModalOpen={isModalOpen}
                    setIsModalOpen={setIsModalOpen}
                />
            )}
        </>
    )
}
