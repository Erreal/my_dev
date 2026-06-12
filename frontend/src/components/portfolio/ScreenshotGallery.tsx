"use client";

import { useState } from "react";
import type { ProjectScreenshot, Locale } from "@/lib/types";
import { getLocalizedField } from "@/lib/i18n";
import { Lightbox } from "@/components/portfolio/Lightbox";

interface ScreenshotGalleryProps {
  screenshots: ProjectScreenshot[];
  locale: Locale;
  projectTitle: string;
}

export function ScreenshotGallery({
  screenshots,
  locale,
  projectTitle,
}: ScreenshotGalleryProps) {
  const [lightboxIndex, setLightboxIndex] = useState<number | null>(null);

  if (!screenshots || screenshots.length === 0) return null;

  return (
    <>
      <div className="mb-12 grid gap-4 md:grid-cols-2">
        {screenshots.map((screenshot, index) => {
          const screenshotTitle = getLocalizedField(
            screenshot.title_ru,
            screenshot.title_en,
            locale
          );
          return (
            <button
              key={screenshot.id}
              onClick={() => setLightboxIndex(index)}
              className="aspect-video bg-muted rounded-lg overflow-hidden group cursor-pointer text-left"
              aria-label={`Open ${screenshotTitle ?? `screenshot ${index + 1}`}`}
            >
              <img
                src={`/screenshots/${screenshot.filename}`}
                alt={screenshotTitle ?? projectTitle}
                className="h-full w-full object-cover transition-transform group-hover:scale-105"
              />
            </button>
          );
        })}
      </div>

      {/* Lightbox */}
      {lightboxIndex !== null && (
        <Lightbox
          screenshots={screenshots}
          initialIndex={lightboxIndex}
          locale={locale}
          onClose={() => setLightboxIndex(null)}
        />
      )}
    </>
  );
}