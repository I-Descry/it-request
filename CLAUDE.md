# AI EXECUTION RULES (LOW TOKEN MODE - ANTEGRAVITY OPTIMIZED)

---

## CORE RULE
- Minimize token usage at all times.
- Prioritize correctness over explanation.
- Do not be verbose.
- Never output full files unless explicitly requested.

---

## OUTPUT FORMAT
- Default: code only
- If modifying code: return DIFF only
- No explanations unless explicitly asked
- No repetition of user input

---

## CONTEXT USAGE RULE (VERY IMPORTANT)
- Use ONLY the minimum required project context.
- Do NOT scan or analyze multiple files unless necessary.
- Do NOT load full controllers/services unless needed.
- Prefer reading only relevant functions or sections of a file.
- Avoid broad project-wide analysis.

---

## FILE ACCESS RULE
- Find the exact location of the issue first.
- Only open the file(s) directly related to the problem.
- One task = one file or one small section.

---

## CODE RULES (Laravel / PHP)
- One request = one function or one file only.
- Avoid rewriting full controllers/models.
- Prefer patch-level edits over full rewrites.
- Do not refactor unrelated code.

---

## DEBUGGING RULE
- Output ONLY:
  - root cause
  - fix (code or diff only)
- No stack trace explanation unless requested

---

## TOKEN CONTROL RULE
- Avoid multi-file changes in a single response.
- Avoid system-wide refactors unless explicitly requested.
- Keep responses minimal and targeted.

---

## PROHIBITIONS
- No full file rewrites (unless explicitly asked)
- No summaries
- No "here is the improved version of everything"
- No unnecessary refactoring
- No broad architectural analysis unless requested

---

## UNKNOWN CONTEXT RULE
If the required file or location is unclear:
- Ask a direct question OR
- Suggest up to 2 likely locations only
- Do NOT guess or rewrite random files

---

## FINAL SYSTEM RULE
- Smallest possible context only
- One feature = one file section
- One response = one patch
- Stay strictly focused on the requested change