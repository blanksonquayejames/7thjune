import { NextRequest, NextResponse } from 'next/server';
import { dbQuery } from '../../../../lib/db';
import type { User } from '../../../../lib/types';

export async function POST(request: NextRequest) {
  try {
    const { email, password } = await request.json();
    if (!email || !password) {
      return NextResponse.json({ error: 'Email and password are required' }, { status: 400 });
    }

    const users = await dbQuery<User>(
      'SELECT id, name, email, password, role FROM users WHERE email = $1 AND password = $2',
      [email, password]
    );

    if (users.length === 0) {
      return NextResponse.json({ error: 'Invalid email or password' }, { status: 401 });
    }

    const user = users[0];
    return NextResponse.json({
      id: user.id,
      name: user.name,
      email: user.email,
      role: user.role
    });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
