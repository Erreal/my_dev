"use client";

import { useState, useCallback, useEffect } from "react";
import type { ProjectScreenshot, Locale } from "@/lib/types";
import { getLocalizedField } from "@/lib/i18n";
import { ChevronLeft, ChevronRight, XIcon } from "lucide-react";

interface LightboxProps {
  screenshots: ProjectScreenshot[];
  initialIndex: number;
  locale: Locale;
  onClose: () => void;
}

export function Lightbox({
  screenshots,
  initialIndex,
  locale,
  onClose,
}: LightboxProps) {
  const [currentIndex, setCurrentIndex] = useState(initialIndex);

  const goToPrev = useCallback(() => {
    setCurrentIndex((prev) =>
      prev === 0 ? screenshots.length - 1 : prev - 1
    );
  }, [screenshots.length]);

  const goToNext = useCallback(() => {
    setCurrentIndex((prev) =>
      prev === screenshots.length - 1 ? 0 : prev + 1
    );
  }, [screenshots.length]);

  // Keyboard navigation
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      switch (e.key) {
        case "Escape":
          onClose();
          break;
        case "ArrowLeft":
          goToPrev();
          break;
        case "ArrowRight":
          goToNext();
          break;
      }
    };

    document.addEventListener("keydown", handleKeyDown);
    document.body.style.overflow = "hidden";

    return () => {
      document.removeEventListener("keydown", handleKeyDown);
      document.body.style.overflow = "";
    };
  }, [onClose, goToPrev, goToNext]);

  const current = screenshots[currentIndex];
  const title = getLocalizedField(current.title_ru, current.title_en, locale);

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
      onClick={onClose}
      role="dialog"
      aria-modal="true"
      aria-label={title ?? "Screenshot"}
    >
      {/* Close button */}
      <button
        onClick={onClose}
        className="absolute top-4 right-4 z-10 rounded-full p-2 text-white/70 hover:text-white hover:bg-white/10 transition-colors"
        aria-label="Close"
      >
        <XIcon className="h-6 w-6" />
      </button>

      {/* Counter */}
      <div className="absolute top-4 left-4 z-10 text-sm text-white/70">
        {currentIndex + 1} / {screenshots.length}
      </div>

      {/* Previous button */}
      {screenshots.length > 1 && (
        <button
          onClick={(e) => {
            e.stopPropagation();
            goToPrev();
          }}
          className="absolute left-4 z-10 rounded-full p-2 text-white/70 hover:text-white hover:bg-white/10 transition-colors"
          aria-label="Previous"
        >
          <ChevronLeft className="h-8 w-8" />
        </button>
      )}

      {/* Image */}
      <div
        className="relative max-h-[90vh] max-w-[90vw]"
        onClick={(e) => e.stopPropagation()}
      >
        <img
          src={`/screenshots/${current.filename}`}
          alt={title ?? `Screenshot ${currentIndex + 1}`}
          className="max-h-[85vh] max-w-[90vw] rounded-lg object-contain"
        />
        {title && (
          <p className="mt-3 text-center text-sm text-white/70">{title}</p>
        )}
      </div>

      {/* Next button */}
      {screenshots.length > 1 && (
        <button
          onClick={(e) => {
            e.stopPropagation();
            goToNext();
          }}
          className="absolute right-4 z-10 rounded-full p-2 text-white/70 hover:text-white hover:bg-white/10 transition-colors"
          aria-label="Next"
        >
          <ChevronRight className="h-8 w-8" />
        </button>
      )}
    </div>
  );
}