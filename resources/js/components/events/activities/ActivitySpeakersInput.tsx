import { Plus, Trash } from "lucide-react";
import { useEffect, useState } from "react";
import { Button } from "@/components/ui/shadcn/button";
import { Item, ItemActions, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/shadcn/item";
import type { Speaker } from "@/types";
import ActivitySpeakerModal from "./ActivitySpeakerModal";

type ActivitySpeakerInputProps = {
    onChange: (speakers: Speaker[]) => void;
    value: Speaker[];
}

export default function ActivitySpeakersInput({ onChange, value }: ActivitySpeakerInputProps) {
    const [speakers, setSpeakers] = useState<Speaker[]>(value);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingSpeaker, setEditingSpeaker] = useState<Speaker | null>();

    useEffect(() => {
        onChange(speakers);
    }, [speakers]);

    const handleAddSpeaker = (speaker: Speaker) => {
        if (editingSpeaker) {
            setSpeakers(speakers.map((s) => s.id === speaker.id ? speaker : s));
        } else {
            setSpeakers([...speakers, speaker]);
        }

        setEditingSpeaker(null);
        setIsModalOpen(false);
    }


    const handleDeleteSpeaker = (speakerId: string) => {
        setSpeakers(speakers.filter((speaker) => speaker.id !== speakerId));
    }

    const handleSetEditingSpeaker = (speaker: Speaker) => {
        setEditingSpeaker(speaker);
        setIsModalOpen(true);
    }

    const handleToggleModal = (open: boolean) => {
        setEditingSpeaker(null);
        setIsModalOpen(open);
    }

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
                    <div className="flex flex-wrap gap-2 my-4">
                        {speakers.map((speaker) => (
                            <Item
                                key={speaker.id}
                                variant="outline"
                                onDoubleClick={() => handleSetEditingSpeaker(speaker)}
                            >
                                <ItemContent>
                                    <ItemTitle>{speaker.name} {speaker.father_last_name} {speaker.mother_last_name}</ItemTitle>
                                    <ItemDescription>{speaker.email}</ItemDescription>
                                </ItemContent>

                                <ItemActions>
                                    <Button
                                        onClick={() => handleDeleteSpeaker(speaker.id)}
                                        type="button"
                                        variant="destructive"
                                    >
                                        <Trash className="size-4" />
                                    </Button>
                                </ItemActions>
                            </Item>
                        ))}
                    </div>
                )}
            </div>

            <ActivitySpeakerModal
                handleAddSpeaker={handleAddSpeaker}
                isModalOpen={isModalOpen}
                editingSpeaker={editingSpeaker}
                setIsModalOpen={handleToggleModal}
            />
        </>
    )
}
