import { create } from 'zustand';
import { calendarService } from '@modules/calendar/services/calendarService';
import type { CalendarEvent } from '@modules/calendar/types/calendar';

interface CalendarState {
    events: CalendarEvent[];
    loading: boolean;
    error: string | null;
    fetchEvents: () => Promise<void>;
}

export const useCalendarStore = create<CalendarState>((set) => ({
    events: [],
    loading: false,
    error: null,

    fetchEvents: async () => {
        set({ loading: true, error: null });
        try {
            const events = await calendarService.listEvents();
            set({ events, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load calendar events.',
            });
        }
    },
}));
