"use client";

import { Moon, Sun } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useEffect, useRef, useState } from "react";

function getInitialTheme(): boolean {
  try {
    const stored = localStorage.getItem("theme");
    const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    return stored === "dark" || (!stored && prefersDark);
  } catch {
    return false;
  }
}

export function ThemeSwitcher() {
  const [isDark, setIsDark] = useState(false);
  const initialized = useRef(false);

  // Sync DOM class with state changes
  useEffect(() => {
    document.documentElement.classList.toggle("dark", isDark);
    localStorage.setItem("theme", isDark ? "dark" : "light");
  }, [isDark]);

  // Initialize from storage on mount
  useEffect(() => {
    if (initialized.current) return;
    initialized.current = true;

    const initial = getInitialTheme();
    if (initial) {
      document.documentElement.classList.add("dark");
    }
    setIsDark(initial);
  }, []);

  const toggleTheme = () => {
    setIsDark((prev) => !prev);
  };

  return (
    <Button variant="ghost" size="sm" onClick={toggleTheme} aria-label="Toggle theme">
      {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
    </Button>
  );
}